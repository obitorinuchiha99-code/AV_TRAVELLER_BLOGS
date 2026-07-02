<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

function api_find_car(int $id): ?array
{
    $car = db_one('SELECT * FROM cars WHERE id = ? AND is_active = 1', [$id]);
    if ($car) {
        return $car;
    }

    foreach (default_cars() as $fallback) {
        if ((int) $fallback['id'] === $id) {
            return $fallback;
        }
    }

    return null;
}

function api_booking_dates(string $start, string $end): array
{
    $period = [];
    $current = new DateTime($start);
    $last = new DateTime($end);
    while ($current <= $last) {
        $period[] = $current->format('Y-m-d');
        $current->modify('+1 day');
    }
    return $period;
}

function api_create_razorpay_order(string $bookingCode, float $amount): array
{
    $amountPaise = max(100, (int) round($amount * 100));

    if (!RAZORPAY_ENABLED || !function_exists('curl_init')) {
        return [
            'booking_code' => $bookingCode,
            'razorpay_order_id' => 'demo_order',
            'amount_paise' => $amountPaise,
        ];
    }

    $payload = json_encode([
        'amount' => $amountPaise,
        'currency' => 'INR',
        'receipt' => $bookingCode,
        'notes' => ['booking_code' => $bookingCode],
    ]);

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 15,
    ]);
    $raw = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $response = $raw ? json_decode($raw, true) : null;
    if ($status >= 200 && $status < 300 && isset($response['id'])) {
        return [
            'booking_code' => $bookingCode,
            'razorpay_order_id' => $response['id'],
            'amount_paise' => $amountPaise,
        ];
    }

    return [
        'booking_code' => $bookingCode,
        'razorpay_order_id' => 'demo_order',
        'amount_paise' => $amountPaise,
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['message' => 'Method not allowed.'], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    json_response(['message' => 'Security token expired. Refresh and try again.'], 419);
}

$customerName = clean_text($_POST['customer_name'] ?? '', 160);
$phone = clean_phone($_POST['phone'] ?? '');
$whatsapp = clean_phone($_POST['whatsapp'] ?? '');
$pickupDate = clean_text($_POST['pickup_date'] ?? '', 20);
$returnDate = clean_text($_POST['return_date'] ?? '', 20);
$pickupLocation = clean_text($_POST['pickup_location'] ?? '', 255);
$dropLocation = clean_text($_POST['drop_location'] ?? '', 255);
$carId = (int) ($_POST['car_id'] ?? 0);
$driverRequired = (int) ($_POST['driver_required'] ?? 0) === 1;
$message = clean_text($_POST['message'] ?? '', 1000);
$paymentMethod = clean_text($_POST['payment_method'] ?? 'razorpay', 20);

if (
    $customerName === '' ||
    $phone === '' ||
    $whatsapp === '' ||
    !valid_date($pickupDate) ||
    !valid_date($returnDate) ||
    $pickupLocation === '' ||
    $dropLocation === '' ||
    $carId <= 0
) {
    json_response(['message' => 'Please complete all required booking fields.'], 422);
}

if (strtotime($returnDate) < strtotime($pickupDate)) {
    json_response(['message' => 'Return date must be the same as or after pickup date.'], 422);
}

$car = api_find_car($carId);
if (!$car) {
    json_response(['message' => 'Selected car is unavailable.'], 404);
}

$blocked = db_one(
    'SELECT unavailable_date FROM availability WHERE car_id = ? AND unavailable_date BETWEEN ? AND ? LIMIT 1',
    [$carId, $pickupDate, $returnDate]
);
if ($blocked) {
    json_response(['message' => 'Selected car has unavailable dates in this range. Please choose another date.'], 409);
}

$days = max(1, count(api_booking_dates($pickupDate, $returnDate)));
$driverCharge = $driverRequired ? 800 * $days : 0;
$estimate = ((float) $car['price_per_day'] * $days) + $driverCharge;
$bookingCode = booking_code();
$bookingId = null;

$pdo = db();
if ($pdo) {
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO customers (name, phone, whatsapp) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE name = VALUES(name), whatsapp = VALUES(whatsapp)'
        );
        $stmt->execute([$customerName, $phone, $whatsapp]);
        $customerId = (int) $pdo->lastInsertId();
        if ($customerId === 0) {
            $customer = db_one('SELECT id FROM customers WHERE phone = ?', [$phone]);
            $customerId = (int) ($customer['id'] ?? 0);
        }

        $stmt = $pdo->prepare(
            'INSERT INTO bookings
             (booking_code, customer_id, customer_name, phone, whatsapp, pickup_date, return_date, pickup_location, drop_location, car_id, car_name, driver_required, message, estimated_amount)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $bookingCode,
            $customerId ?: null,
            $customerName,
            $phone,
            $whatsapp,
            $pickupDate,
            $returnDate,
            $pickupLocation,
            $dropLocation,
            $carId,
            $car['name'],
            $driverRequired ? 1 : 0,
            $message,
            $estimate,
        ]);
        $bookingId = (int) $pdo->lastInsertId();

        $availability = $pdo->prepare('INSERT IGNORE INTO availability (car_id, unavailable_date, status, note) VALUES (?, ?, ?, ?)');
        foreach (api_booking_dates($pickupDate, $returnDate) as $date) {
            $availability->execute([$carId, $date, 'Booked', 'Booking ' . $bookingCode]);
        }

        $pdo->prepare('UPDATE cars SET availability_status = ? WHERE id = ?')->execute(['Booked', $carId]);
        $pdo->prepare('INSERT INTO admin_notifications (title, message, type) VALUES (?, ?, ?)')->execute([
            'New booking received',
            "{$customerName} requested {$car['name']} from {$pickupDate} to {$returnDate}.",
            'Booking',
        ]);

        $pdo->commit();
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Booking insert failed: ' . $exception->getMessage());
        json_response(['message' => 'Could not save booking. Please try again or WhatsApp us.'], 500);
    }
}

$payment = null;
if ($paymentMethod === 'razorpay') {
    $payment = api_create_razorpay_order($bookingCode, $estimate);
    db_execute(
        'INSERT INTO payments (booking_id, booking_code, provider, razorpay_order_id, amount, status) VALUES (?, ?, ?, ?, ?, ?)',
        [$bookingId, $bookingCode, 'Razorpay', $payment['razorpay_order_id'], $estimate, 'Created']
    );
} elseif ($paymentMethod === 'upi') {
    db_execute(
        'INSERT INTO payments (booking_id, booking_code, provider, amount, status) VALUES (?, ?, ?, ?, ?)',
        [$bookingId, $bookingCode, 'UPI', $estimate, 'Created']
    );
} else {
    db_execute(
        'INSERT INTO payments (booking_id, booking_code, provider, amount, status) VALUES (?, ?, ?, ?, ?)',
        [$bookingId, $bookingCode, 'Cash', $estimate, 'Cash Pending']
    );
}

$whatsappMessage = "Hello AV Traveller Vlogs,\nI want to confirm my rental car booking.\n\nBooking Code: {$bookingCode}\nName: {$customerName}\nCar: {$car['name']}\nPickup: {$pickupDate} from {$pickupLocation}\nReturn: {$returnDate}\nEstimated Amount: " . money_inr($estimate);

json_response([
    'message' => 'Booking created. Confirmation is ready on WhatsApp.',
    'booking_code' => $bookingCode,
    'estimated_amount' => $estimate,
    'whatsapp_url' => whatsapp_link($whatsappMessage),
    'payment' => $payment ? array_merge($payment, [
        'amount' => $estimate,
        'customer_name' => $customerName,
        'phone' => $phone,
    ]) : null,
]);


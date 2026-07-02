<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['message' => 'Method not allowed.'], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    json_response(['message' => 'Security token expired. Refresh and try again.'], 419);
}

$bookingCode = strtoupper(clean_text($_POST['booking_code'] ?? '', 40));
$phone = clean_phone($_POST['phone'] ?? '');

if ($bookingCode === '' || $phone === '') {
    json_response(['message' => 'Booking code and phone are required.'], 422);
}

$booking = db_one('SELECT id, booking_code, customer_name, phone, estimated_amount FROM bookings WHERE booking_code = ? AND phone = ?', [$bookingCode, $phone]);
if (!$booking) {
    json_response(['message' => 'Booking not found.'], 404);
}

$amountPaise = max(100, (int) round(((float) $booking['estimated_amount']) * 100));
$orderId = 'demo_order';

if (RAZORPAY_ENABLED && function_exists('curl_init')) {
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
        $orderId = $response['id'];
    }
}

db_execute(
    'INSERT INTO payments (booking_id, booking_code, provider, razorpay_order_id, amount, status) VALUES (?, ?, ?, ?, ?, ?)',
    [$booking['id'], $bookingCode, 'Razorpay', $orderId, $booking['estimated_amount'], 'Created']
);

json_response([
    'booking_code' => $bookingCode,
    'customer_name' => $booking['customer_name'],
    'phone' => $booking['phone'],
    'razorpay_order_id' => $orderId,
    'amount_paise' => $amountPaise,
]);

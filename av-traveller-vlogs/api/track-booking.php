<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['message' => 'Method not allowed.'], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    json_response(['message' => 'Security token expired. Refresh and try again.'], 419);
}

$code = strtoupper(clean_text($_POST['booking_code'] ?? '', 40));
$phone = clean_phone($_POST['phone'] ?? '');

if ($code === '' || $phone === '') {
    json_response(['message' => 'Booking code and phone number are required.'], 422);
}

$booking = db_one(
    'SELECT booking_code, customer_name, phone, pickup_date, return_date, pickup_location, drop_location, car_name, driver_required, estimated_amount, status, payment_status, created_at FROM bookings WHERE booking_code = ? AND phone = ?',
    [$code, $phone]
);

if (!$booking) {
    json_response(['message' => 'No booking found for this code and phone number.'], 404);
}

json_response(['booking' => $booking]);


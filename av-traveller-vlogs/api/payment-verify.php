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
$paymentId = clean_text($_POST['razorpay_payment_id'] ?? '', 120);
$orderId = clean_text($_POST['razorpay_order_id'] ?? '', 120);
$signature = clean_text($_POST['razorpay_signature'] ?? '', 255);

if ($bookingCode === '' || $paymentId === '' || $orderId === '') {
    json_response(['message' => 'Payment details are incomplete.'], 422);
}

$verified = true;
if (RAZORPAY_ENABLED && $orderId !== 'demo_order') {
    $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
    $verified = hash_equals($expected, $signature);
}

if (!$verified) {
    db_execute('UPDATE payments SET status = ? WHERE booking_code = ? AND razorpay_order_id = ?', ['Failed', $bookingCode, $orderId]);
    json_response(['message' => 'Payment verification failed.'], 400);
}

db_execute(
    'UPDATE payments SET razorpay_payment_id = ?, razorpay_signature = ?, status = ?, paid_at = NOW() WHERE booking_code = ? AND razorpay_order_id = ?',
    [$paymentId, $signature, 'Success', $bookingCode, $orderId]
);
db_execute('UPDATE bookings SET payment_status = ? WHERE booking_code = ?', ['Paid', $bookingCode]);
db_execute('INSERT INTO admin_notifications (title, message, type) VALUES (?, ?, ?)', ['Payment successful', "Payment verified for {$bookingCode}.", 'Payment']);

json_response(['message' => 'Payment verified.', 'booking_code' => $bookingCode]);


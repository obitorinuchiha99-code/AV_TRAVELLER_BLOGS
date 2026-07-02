<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

$payload = file_get_contents('php://input') ?: '';
$signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

if (RAZORPAY_ENABLED && $signature !== '') {
    $expected = hash_hmac('sha256', $payload, RAZORPAY_KEY_SECRET);
    if (!hash_equals($expected, $signature)) {
        json_response(['message' => 'Invalid signature.'], 400);
    }
}

$event = json_decode($payload, true);
$payment = $event['payload']['payment']['entity'] ?? null;

if (!$payment) {
    json_response(['message' => 'Ignored.']);
}

$orderId = clean_text($payment['order_id'] ?? '', 120);
$paymentId = clean_text($payment['id'] ?? '', 120);

if ($orderId !== '') {
    db_execute(
        'UPDATE payments SET razorpay_payment_id = ?, status = ?, paid_at = NOW() WHERE razorpay_order_id = ?',
        [$paymentId, 'Success', $orderId]
    );
    $row = db_one('SELECT booking_code FROM payments WHERE razorpay_order_id = ?', [$orderId]);
    if ($row) {
        db_execute('UPDATE bookings SET payment_status = ? WHERE booking_code = ?', ['Paid', $row['booking_code']]);
    }
}

json_response(['message' => 'Webhook processed.']);

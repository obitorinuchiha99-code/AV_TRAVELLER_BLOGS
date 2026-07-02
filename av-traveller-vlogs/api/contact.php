<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['message' => 'Method not allowed.'], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    json_response(['message' => 'Security token expired. Refresh and try again.'], 419);
}

$name = clean_text($_POST['name'] ?? '', 160);
$phone = clean_phone($_POST['phone'] ?? '');
$emailRaw = trim((string) ($_POST['email'] ?? ''));
$email = $emailRaw !== '' ? filter_var($emailRaw, FILTER_VALIDATE_EMAIL) : null;
$subject = clean_text($_POST['subject'] ?? 'Website Enquiry', 180);
$message = clean_text($_POST['message'] ?? '', 1500);

if ($name === '' || $phone === '' || $message === '') {
    json_response(['message' => 'Name, phone and message are required.'], 422);
}

db_execute(
    'INSERT INTO contact_messages (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)',
    [$name, $phone, $email ?: null, $subject, $message]
);
db_execute(
    'INSERT INTO admin_notifications (title, message, type) VALUES (?, ?, ?)',
    ['New contact message', "{$name} sent a contact enquiry.", 'Contact']
);

json_response(['message' => 'Message sent. We will respond shortly on phone or WhatsApp.']);


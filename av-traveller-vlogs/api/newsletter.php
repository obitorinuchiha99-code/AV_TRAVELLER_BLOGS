<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['message' => 'Method not allowed.'], 405);
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    json_response(['message' => 'Security token expired. Refresh and try again.'], 419);
}

$email = filter_var(trim((string) ($_POST['email'] ?? '')), FILTER_VALIDATE_EMAIL);
if (!$email) {
    json_response(['message' => 'Enter a valid email address.'], 422);
}

db_execute('INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)', [$email]);
json_response(['message' => 'Subscribed. We will send only useful travel updates.']);


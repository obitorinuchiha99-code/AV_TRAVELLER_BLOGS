<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/admin-functions.php';
require_admin();

$type = $_GET['type'] ?? 'bookings';
$allowed = [
    'bookings' => 'SELECT * FROM bookings ORDER BY id DESC',
    'customers' => 'SELECT * FROM customers ORDER BY id DESC',
    'cars' => 'SELECT * FROM cars ORDER BY id DESC',
    'payments' => 'SELECT * FROM payments ORDER BY id DESC',
];

if (!isset($allowed[$type])) {
    $type = 'bookings';
}

$rows = db_all($allowed[$type]);
$filename = 'av-traveller-' . $type . '-' . date('Ymd-His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
if ($rows) {
    fputcsv($out, array_keys($rows[0]));
    foreach ($rows as $row) {
        fputcsv($out, $row);
    }
} else {
    fputcsv($out, ['message']);
    fputcsv($out, ['No rows found or database is not connected.']);
}
fclose($out);
exit;

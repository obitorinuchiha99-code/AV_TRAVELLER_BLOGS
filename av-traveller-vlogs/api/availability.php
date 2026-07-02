<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

$carId = (int) ($_GET['car_id'] ?? 0);
if ($carId <= 0) {
    json_response(['dates' => []]);
}

$rows = db_all(
    'SELECT unavailable_date FROM availability WHERE car_id = ? AND unavailable_date >= CURRENT_DATE ORDER BY unavailable_date',
    [$carId]
);

if (!$rows && in_array($carId, [3, 6], true)) {
    $offsets = $carId === 3 ? [2, 3] : [1];
    $dates = array_map(static fn (int $days): string => date('Y-m-d', strtotime("+{$days} days")), $offsets);
    json_response(['dates' => $dates]);
}

json_response(['dates' => array_column($rows, 'unavailable_date')]);


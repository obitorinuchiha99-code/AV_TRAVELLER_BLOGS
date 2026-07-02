<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/data.php';

json_response(['cars' => get_cars()]);


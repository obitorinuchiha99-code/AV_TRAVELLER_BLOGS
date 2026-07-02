<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/functions.php';

unset($_SESSION[ADMIN_SESSION_KEY]);
session_regenerate_id(true);
header('Location: login.php');
exit;


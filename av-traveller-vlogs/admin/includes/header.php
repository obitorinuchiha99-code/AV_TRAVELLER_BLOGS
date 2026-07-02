<?php
declare(strict_types=1);

require_once __DIR__ . '/admin-functions.php';
require_admin();
$adminPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
$admin = current_admin();
$flash = flash();
$adminNav = [
    'dashboard.php' => ['Dashboard', 'fa-chart-line'],
    'cars.php' => ['Cars', 'fa-car'],
    'bookings.php' => ['Bookings', 'fa-calendar-check'],
    'availability.php' => ['Availability', 'fa-calendar-days'],
    'packages.php' => ['Packages', 'fa-route'],
    'gallery.php' => ['Gallery', 'fa-images'],
    'reviews.php' => ['Reviews', 'fa-star'],
    'vlogs.php' => ['Vlogs', 'fa-video'],
    'customers.php' => ['Customers', 'fa-users'],
];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($adminTitle ?? 'Admin') ?> | AV Traveller Vlogs</title>
    <link rel="icon" href="../assets/images/favicon.svg" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">
<aside class="admin-sidebar">
    <a class="admin-brand" href="dashboard.php">
        <img src="../assets/images/logo.svg" alt="AV Traveller Vlogs">
        <span>AV Admin</span>
    </a>
    <nav>
        <?php foreach ($adminNav as $href => [$label, $icon]): ?>
            <a class="<?= $adminPage === $href ? 'active' : '' ?>" href="<?= e($href) ?>">
                <i class="fa-solid <?= e($icon) ?>"></i><?= e($label) ?>
            </a>
        <?php endforeach; ?>
        <a href="export.php"><i class="fa-solid fa-file-csv"></i>Export CSV</a>
        <a href="../index.php" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i>View Site</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </nav>
</aside>
<div class="admin-main">
    <header class="admin-topbar">
        <button class="admin-menu" id="adminMenu" type="button" aria-label="Open menu"><i class="fa-solid fa-bars"></i></button>
        <div>
            <strong><?= e($adminTitle ?? 'Dashboard') ?></strong>
            <span><?= e($admin['email'] ?? '') ?></span>
        </div>
    </header>
    <main class="admin-content">
        <?php if ($flash): ?>
            <div class="alert alert-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
        <?php endif; ?>

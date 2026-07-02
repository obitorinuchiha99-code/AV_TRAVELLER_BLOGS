<?php
declare(strict_types=1);

require_once __DIR__ . '/data.php';

$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$pageTitle = $pageTitle ?? APP_NAME . ' | Premium Car Rental & Surat Travel Guide';
$metaDescription = $metaDescription ?? 'Book premium rental cars, Surat tour packages and local travel experiences with AV Traveller Vlogs.';
$pageImage = $pageImage ?? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80';
$canonical = $canonical ?? rtrim(APP_URL, '/') . '/' . ($currentPage === 'index.php' ? '' : $currentPage);

$navItems = [
    'index.php' => 'Home',
    'cars.php' => 'Cars',
    'tour-packages.php' => 'Tour Packages',
    'travel-vlogs.php' => 'Travel Vlogs',
    'gallery.php' => 'Gallery',
    'places.php' => 'Places',
    'about.php' => 'About',
    'contact.php' => 'Contact',
];
?>
<!doctype html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="Surat car rental, AV Traveller Vlogs, Toyota Innova rental Surat, Fortuner rental Surat, Surat travel agency, Surat tour guide">
    <meta name="author" content="AV Traveller Vlogs">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= e($canonical) ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e($pageImage) ?>">
    <meta property="og:site_name" content="<?= e(APP_NAME) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($pageTitle) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="<?= e($pageImage) ?>">

    <title><?= e($pageTitle) ?></title>

    <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#061527">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="AV Traveller">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "AV Traveller Vlogs",
        "url": "<?= e(rtrim(APP_URL, '/')) ?>",
        "logo": "<?= e(asset('assets/images/logo.svg')) ?>",
        "image": "<?= e($pageImage) ?>",
        "telephone": "<?= e(PRIMARY_PHONE) ?>",
        "email": "<?= e(CONTACT_EMAIL) ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Surat",
            "addressRegion": "Gujarat",
            "addressCountry": "IN"
        },
        "areaServed": "Surat, Gujarat",
        "sameAs": [
            <?= json_encode(INSTAGRAM_URL) ?>,
            <?= json_encode(FACEBOOK_URL) ?>,
            <?= json_encode(YOUTUBE_URL) ?>
        ]
    }
    </script>
</head>
<body>
<div class="page-loader" aria-hidden="true">
    <div class="loader-mark">
        <img src="assets/images/logo.svg" alt="">
        <span></span>
    </div>
</div>

<nav class="navbar navbar-expand-xl fixed-top glass-nav" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand brand-lockup" href="index.php" aria-label="AV Traveller Vlogs home">
            <img src="assets/images/logo.svg" alt="AV Traveller Vlogs logo" width="48" height="48">
            <span>AV Traveller <small>Vlogs</small></span>
        </a>
        <div class="d-flex align-items-center gap-2 order-xl-3">
            <select class="form-select form-select-sm language-select" id="languageSelect" aria-label="Language switcher">
                <option value="en">EN</option>
                <option value="hi">हिंदी</option>
                <option value="gu">ગુજરાતી</option>
            </select>
            <button class="icon-btn" id="themeToggle" type="button" aria-label="Toggle dark mode" title="Toggle theme">
                <i class="fa-solid fa-moon"></i>
            </button>
            <a class="btn btn-primary-gradient d-none d-md-inline-flex" href="booking.php" data-i18n="navBook">Book Now</a>
            <button class="navbar-toggler icon-btn" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse order-xl-2" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-xl-0">
                <?php foreach ($navItems as $href => $label): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $currentPage === $href ? 'active' : '' ?>" href="<?= e($href) ?>"><?= e($label) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <a class="btn btn-primary-gradient w-100 d-md-none mt-2" href="booking.php" data-i18n="navBookMobile">Book Now</a>
        </div>
    </div>
</nav>

<main>

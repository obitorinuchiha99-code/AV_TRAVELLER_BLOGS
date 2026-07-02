<?php
$pageTitle = 'Premium Rental Cars in Surat | AV Traveller Vlogs';
$metaDescription = 'Browse premium rental cars in Surat including Innova Crysta, Fortuner, Carnival, Ertiga, Creta and XUV700.';
require_once __DIR__ . '/includes/header.php';
$cars = get_cars();
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Fleet</span>
        <h1>Cars ready for city, family and outstation routes</h1>
        <p>Compare seats, fuel, transmission, price and live availability before booking.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="fleet-toolbar glass-panel">
            <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" id="carSearch" placeholder="Search cars"></div>
            <select id="carFilter"><option value="all">All Cars</option><option value="Available">Available</option><option value="Booked">Booked</option><option value="Maintenance">Maintenance</option></select>
            <select id="carSort"><option value="default">Sort by default</option><option value="low">Price: Low to High</option><option value="high">Price: High to Low</option></select>
        </div>
        <div class="row g-4 car-grid mt-1">
            <?php foreach ($cars as $car): ?>
                <?php $status = (string) $car['availability_status']; ?>
                <div class="col-md-6 col-xl-4 car-item" data-name="<?= e(strtolower($car['name'] . ' ' . $car['fuel'] . ' ' . $car['category'])) ?>" data-status="<?= e($status) ?>" data-price="<?= e((string) $car['price_per_day']) ?>">
                    <article class="premium-card car-card">
                        <div class="card-media">
                            <img src="<?= e($car['image_url']) ?>" alt="<?= e($car['name']) ?>" loading="lazy">
                            <span class="status-badge status-<?= e(strtolower($status)) ?>"><?= e($status) ?></span>
                        </div>
                        <div class="card-body">
                            <span class="card-kicker"><?= e($car['category']) ?></span>
                            <h2><?= e($car['name']) ?></h2>
                            <p><?= e($car['description']) ?></p>
                            <div class="spec-grid">
                                <span><i class="fa-solid fa-users"></i><?= (int) $car['seats'] ?> Seats</span>
                                <span><i class="fa-solid fa-gas-pump"></i><?= e($car['fuel']) ?></span>
                                <span><i class="fa-solid fa-gears"></i><?= e($car['transmission']) ?></span>
                            </div>
                            <div class="card-bottom">
                                <strong><?= money_inr($car['price_per_day']) ?><small>/day</small></strong>
                                <a class="btn btn-soft" href="booking.php?car=<?= (int) $car['id'] ?>">Book Now</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


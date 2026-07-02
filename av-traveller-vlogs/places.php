<?php
$pageTitle = 'Top Places in Surat | AV Traveller Vlogs';
$metaDescription = 'Explore Dumas Beach, Surat Castle, Science Centre, ISKCON Temple, Ambaji Temple, Dutch Garden, Gopi Talav and Sarthana Nature Park.';
require_once __DIR__ . '/includes/header.php';
$places = default_places();
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Surat Guide</span>
        <h1>Top places in Surat for your next local trip</h1>
        <p>Real places, quick descriptions, route buttons and booking-ready travel ideas.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($places as $place): ?>
                <?php $id = strtolower(str_replace(' ', '-', $place['name'])); ?>
                <div class="col-md-6 col-xl-3" id="<?= e($id) ?>" data-aos="fade-up">
                    <article class="premium-card place-card tall">
                        <img src="<?= e($place['image_url']) ?>" alt="<?= e($place['name']) ?>" loading="lazy">
                        <div class="card-body">
                            <h2><?= e($place['name']) ?></h2>
                            <p><?= e($place['description']) ?></p>
                            <span class="location-line"><i class="fa-solid fa-location-dot"></i><?= e($place['location']) ?></span>
                            <div class="card-actions">
                                <a href="booking.php">Book Route</a>
                                <a href="<?= e($place['maps_url']) ?>" target="_blank" rel="noopener">Google Maps</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


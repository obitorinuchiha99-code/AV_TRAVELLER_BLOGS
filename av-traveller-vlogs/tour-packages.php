<?php
$pageTitle = 'Surat Tour Packages | AV Traveller Vlogs';
$metaDescription = 'Book weekend, family, temple and outstation tour packages from Surat with premium cars and driver support.';
require_once __DIR__ . '/includes/header.php';
$packages = get_packages();
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Tour Packages</span>
        <h1>Curated routes with premium pickup</h1>
        <p>Weekend, family, temple and outstation plans tailored around Surat travellers.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($packages as $package): ?>
                <div class="col-md-6 col-xl-3" data-aos="fade-up">
                    <article class="premium-card package-card">
                        <img src="<?= e($package['image_url']) ?>" alt="<?= e($package['title']) ?>" loading="lazy">
                        <div class="card-body">
                            <span class="card-kicker"><?= e($package['type']) ?> · <?= e($package['duration']) ?></span>
                            <h2><?= e($package['title']) ?></h2>
                            <p><?= e($package['description']) ?></p>
                            <div class="card-bottom">
                                <strong><?= money_inr($package['price']) ?></strong>
                                <a class="btn btn-soft" href="booking.php?package=<?= (int) $package['id'] ?>">Book Now</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


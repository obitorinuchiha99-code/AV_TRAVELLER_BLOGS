<?php
$pageTitle = 'Travel Vlogs | AV Traveller Vlogs';
$metaDescription = 'Watch Surat travel vlogs, car rental experiences and route guides from AV Traveller Vlogs.';
require_once __DIR__ . '/includes/header.php';
$vlogs = get_vlogs();
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Travel Vlogs</span>
        <h1>See the route before you ride</h1>
        <p>YouTube videos, thumbnails and descriptions managed from the admin panel.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <?php foreach ($vlogs as $vlog): ?>
                <div class="col-md-6 col-xl-4" data-aos="fade-up">
                    <article class="premium-card vlog-card">
                        <a class="vlog-cover" href="<?= e($vlog['video_url']) ?>" target="_blank" rel="noopener">
                            <img src="<?= e($vlog['thumbnail_url']) ?>" alt="<?= e($vlog['title']) ?>" loading="lazy">
                            <span><i class="fa-solid fa-play"></i></span>
                        </a>
                        <div class="card-body">
                            <h2><?= e($vlog['title']) ?></h2>
                            <p><?= e($vlog['description']) ?></p>
                            <a href="<?= e($vlog['video_url']) ?>" target="_blank" rel="noopener">Watch on YouTube</a>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


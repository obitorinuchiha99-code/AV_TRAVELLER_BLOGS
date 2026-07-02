<?php
$pageTitle = 'Gallery | AV Traveller Vlogs';
$metaDescription = 'View AV Traveller Vlogs gallery with Surat places, premium cars, road trips and travel memories.';
require_once __DIR__ . '/includes/header.php';
$gallery = gallery_images();
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Gallery</span>
        <h1>Travel moments in a clean masonry view</h1>
        <p>Responsive, lazy-loaded, lightbox-ready gallery images.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="masonry-gallery">
            <?php foreach ($gallery as $item): ?>
                <a class="gallery-item glightbox" href="<?= e($item['image_url']) ?>" data-gallery="gallery-page" data-title="<?= e($item['title']) ?>">
                    <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                    <span><?= e($item['title']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


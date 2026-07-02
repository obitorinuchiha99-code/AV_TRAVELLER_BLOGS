<?php
$pageTitle = 'Contact AV Traveller Vlogs | Car Rental Surat';
$metaDescription = 'Contact AV Traveller Vlogs for car rental, WhatsApp booking, Surat tours and outstation travel.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Contact</span>
        <h1>Talk to AV Traveller Vlogs</h1>
        <p>Call, WhatsApp or send a message for cars, route plans and tour packages.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="glass-panel h-100">
                    <h2>Direct details</h2>
                    <div class="contact-list">
                        <a href="tel:<?= e(PRIMARY_PHONE) ?>"><i class="fa-solid fa-phone"></i><?= e(PRIMARY_PHONE) ?></a>
                        <a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i><?= e(SECONDARY_PHONE) ?></a>
                        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><i class="fa-solid fa-envelope"></i><?= e(CONTACT_EMAIL) ?></a>
                        <a href="<?= e(INSTAGRAM_URL) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-instagram"></i>Instagram</a>
                        <a href="<?= e(FACEBOOK_URL) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-facebook-f"></i>Facebook</a>
                        <a href="<?= e(YOUTUBE_URL) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-youtube"></i>YouTube</a>
                    </div>
                    <iframe class="map-frame compact" src="<?= e(GOOGLE_MAPS_EMBED) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Surat map"></iframe>
                </div>
            </div>
            <div class="col-lg-7">
                <form class="glass-panel contact-form ajax-contact" method="post" action="api/contact.php">
                    <?= csrf_field() ?>
                    <div class="row g-3">
                        <div class="col-md-6"><label for="name">Name</label><input id="name" name="name" type="text" required maxlength="160"></div>
                        <div class="col-md-6"><label for="contactPhone">Phone</label><input id="contactPhone" name="phone" type="tel" required maxlength="18"></div>
                        <div class="col-md-6"><label for="email">Email</label><input id="email" name="email" type="email" maxlength="180"></div>
                        <div class="col-md-6"><label for="subject">Subject</label><input id="subject" name="subject" type="text" maxlength="180"></div>
                        <div class="col-12"><label for="contactMessage">Message</label><textarea id="contactMessage" name="message" rows="6" required maxlength="1500"></textarea></div>
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary-gradient" type="submit"><i class="fa-solid fa-paper-plane"></i> Send Message</button>
                    </div>
                    <p class="form-note contact-status" role="status"></p>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

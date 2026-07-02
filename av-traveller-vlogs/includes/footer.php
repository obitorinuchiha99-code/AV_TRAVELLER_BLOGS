</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-4">
                <a class="footer-brand" href="index.php">
                    <img src="assets/images/logo.svg" alt="AV Traveller Vlogs logo">
                    <span>AV Traveller Vlogs</span>
                </a>
                <p class="muted mt-3">Premium car rental, Surat city tours, outstation routes and travel vlog experiences with fast WhatsApp booking.</p>
                <div class="social-row">
                    <a href="<?= e(INSTAGRAM_URL) ?>" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="<?= e(FACEBOOK_URL) ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="<?= e(YOUTUBE_URL) ?>" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="<?= e(whatsapp_link()) ?>" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h3>Quick Links</h3>
                <a href="cars.php">Cars</a>
                <a href="tour-packages.php">Packages</a>
                <a href="places.php">Places</a>
                <a href="travel-vlogs.php">Vlogs</a>
            </div>
            <div class="col-6 col-lg-2">
                <h3>Support</h3>
                <a href="booking.php">Book Now</a>
                <a href="track-booking.php">Track Booking</a>
                <a href="contact.php">Contact</a>
                <a href="admin/login.php">Admin</a>
            </div>
            <div class="col-lg-4">
                <h3>Newsletter</h3>
                <p class="muted">Get local route ideas, car offers and vlog drops.</p>
                <form class="newsletter-form ajax-newsletter" method="post" action="api/newsletter.php">
                    <?= csrf_field() ?>
                    <label class="visually-hidden" for="newsletterEmail">Email</label>
                    <input id="newsletterEmail" name="email" type="email" placeholder="Enter email address" required>
                    <button class="btn btn-primary-gradient" type="submit">Subscribe</button>
                </form>
                <p class="form-note newsletter-status" role="status"></p>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© <?= date('Y') ?> AV Traveller Vlogs. All rights reserved.</span>
            <span>Surat, Gujarat · <?= e(PRIMARY_PHONE) ?></span>
        </div>
    </div>
</footer>

<a class="floating-action whatsapp" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<a class="floating-action call" href="tel:<?= e(PRIMARY_PHONE) ?>" aria-label="Call AV Traveller Vlogs">
    <i class="fa-solid fa-phone"></i>
</a>
<button class="floating-action back-top" id="backTop" type="button" aria-label="Back to top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<script>
    window.AV_CONFIG = {
        appUrl: <?= json_encode(rtrim(APP_URL, '/')) ?>,
        whatsapp: <?= json_encode(PRIMARY_WHATSAPP) ?>,
        csrf: <?= json_encode(csrf_token()) ?>,
        razorpayKey: <?= json_encode(RAZORPAY_KEY_ID) ?>
    };
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="assets/js/app.js"></script>
<?php if (($extraScript ?? '') !== ''): ?>
    <script src="<?= e($extraScript) ?>"></script>
<?php endif; ?>
</body>
</html>

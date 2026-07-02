<?php
$pageTitle = 'Book a Rental Car | AV Traveller Vlogs';
$metaDescription = 'Book a rental car online with pickup date, return date, car selection, driver option, Razorpay, UPI and WhatsApp confirmation.';
require_once __DIR__ . '/includes/header.php';
$bookingCars = get_cars();
$selectedCar = isset($_GET['car']) ? preg_replace('/[^0-9]/', '', (string) $_GET['car']) : '';
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Online Booking</span>
        <h1>Book your car and get WhatsApp confirmation</h1>
        <p>Availability calendar, rental estimate, payment option and booking code in one secure flow.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-panel booking-panel">
                    <?php require __DIR__ . '/includes/booking-form.php'; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <aside class="glass-panel sticky-side">
                    <h2>Booking support</h2>
                    <p>Need a custom route, wedding fleet or airport pickup? Talk directly on WhatsApp.</p>
                    <div class="contact-list">
                        <a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i><?= e(PRIMARY_PHONE) ?></a>
                        <a href="tel:<?= e(PRIMARY_PHONE) ?>"><i class="fa-solid fa-phone"></i><?= e(PRIMARY_PHONE) ?></a>
                        <a href="track-booking.php"><i class="fa-solid fa-magnifying-glass-location"></i>Track Booking</a>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<?php $extraScript = 'assets/js/booking.js'; require_once __DIR__ . '/includes/footer.php'; ?>


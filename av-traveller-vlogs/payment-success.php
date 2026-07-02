<?php
$pageTitle = 'Payment Success | AV Traveller Vlogs';
$metaDescription = 'Payment confirmation page for AV Traveller Vlogs bookings.';
require_once __DIR__ . '/includes/header.php';
$bookingCode = clean_text($_GET['booking'] ?? '', 40);
?>
<section class="inner-hero success-hero">
    <div class="container">
        <span class="success-icon"><i class="fa-solid fa-check"></i></span>
        <span class="eyebrow">Payment Success</span>
        <h1>Your payment confirmation has been received</h1>
        <p><?= $bookingCode ? 'Booking code: ' . e($bookingCode) : 'Your booking payment has been marked for confirmation.' ?></p>
        <div class="hero-actions justify-content-center">
            <a class="btn btn-primary-gradient" href="track-booking.php">Track Booking</a>
            <a class="btn btn-glass" href="<?= e(whatsapp_link('Hello AV Traveller Vlogs, my payment is complete for booking ' . $bookingCode)) ?>" target="_blank" rel="noopener">Share on WhatsApp</a>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

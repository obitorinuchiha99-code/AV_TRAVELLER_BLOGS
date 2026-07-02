<?php
$pageTitle = 'Track Booking | AV Traveller Vlogs';
$metaDescription = 'Track your AV Traveller Vlogs booking status using your booking code and phone number.';
require_once __DIR__ . '/includes/header.php';
?>
<section class="inner-hero">
    <div class="container">
        <span class="eyebrow">Booking Status</span>
        <h1>Track your booking</h1>
        <p>Enter your booking code and phone number to view current booking and payment status.</p>
    </div>
</section>
<section class="section-pad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <form class="glass-panel tracking-form ajax-track" method="post" action="api/track-booking.php">
                    <?= csrf_field() ?>
                    <label for="bookingCode">Booking Code</label>
                    <input id="bookingCode" name="booking_code" type="text" required placeholder="AV-20260630-ABC123">
                    <label for="trackPhone">Phone</label>
                    <input id="trackPhone" name="phone" type="tel" required placeholder="+91 95120 99573">
                    <div class="form-actions">
                        <button class="btn btn-primary-gradient" type="submit"><i class="fa-solid fa-magnifying-glass-location"></i> Track Booking</button>
                    </div>
                    <div class="tracking-result" id="trackingResult"></div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>


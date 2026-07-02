<?php
declare(strict_types=1);

$bookingCars = $bookingCars ?? get_cars();
$selectedCar = $selectedCar ?? '';
?>
<form class="booking-form ajax-booking" method="post" action="api/booking.php" data-aos="fade-up">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="customerName">Customer Name</label>
            <input id="customerName" name="customer_name" type="text" autocomplete="name" required maxlength="160" placeholder="Full name">
        </div>
        <div class="col-md-3">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" autocomplete="tel" required maxlength="18" placeholder="+91 95120 99573">
        </div>
        <div class="col-md-3">
            <label for="whatsapp">WhatsApp</label>
            <input id="whatsapp" name="whatsapp" type="tel" required maxlength="18" placeholder="+91 95120 99573">
        </div>
        <div class="col-md-3">
            <label for="pickupDate">Pickup Date</label>
            <input id="pickupDate" class="booking-date" name="pickup_date" type="text" required placeholder="Select date">
        </div>
        <div class="col-md-3">
            <label for="returnDate">Return Date</label>
            <input id="returnDate" class="booking-date" name="return_date" type="text" required placeholder="Select date">
        </div>
        <div class="col-md-3">
            <label for="carId">Car</label>
            <select id="carId" name="car_id" required>
                <option value="">Choose car</option>
                <?php foreach ($bookingCars as $car): ?>
                    <option value="<?= (int) $car['id'] ?>" data-price="<?= e((string) $car['price_per_day']) ?>" <?= (string) $selectedCar === (string) $car['id'] ? 'selected' : '' ?>>
                        <?= e($car['name']) ?> · <?= money_inr($car['price_per_day']) ?>/day
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="driverRequired">Driver Required</label>
            <select id="driverRequired" name="driver_required">
                <option value="1">Yes, with driver</option>
                <option value="0">No, self drive enquiry</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="pickupLocation">Pickup Location</label>
            <input id="pickupLocation" name="pickup_location" type="text" required maxlength="255" placeholder="Hotel, home, airport or station">
        </div>
        <div class="col-md-6">
            <label for="dropLocation">Drop Location</label>
            <input id="dropLocation" name="drop_location" type="text" required maxlength="255" placeholder="Destination or return point">
        </div>
        <div class="col-md-6">
            <label for="paymentMethod">Payment Option</label>
            <select id="paymentMethod" name="payment_method">
                <option value="razorpay">Razorpay Online Payment</option>
                <option value="upi">UPI QR</option>
                <option value="cash">Cash on Pickup</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="cost-box">
                <span>Estimated rental</span>
                <strong id="costEstimate">Select car and dates</strong>
            </div>
        </div>
        <div class="col-12">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" maxlength="1000" placeholder="Trip plan, pickup time, luggage, route or special requests"></textarea>
        </div>
    </div>
    <div class="booking-payment-panel mt-3" id="upiPanel" hidden>
        <div>
            <strong>UPI QR</strong>
            <p>Scan and share the payment screenshot on WhatsApp with your booking code.</p>
        </div>
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=upi://pay?pa=9512099573@upi%26pn=AV%20Traveller%20Vlogs%26cu=INR" alt="AV Traveller Vlogs UPI QR">
    </div>
    <div class="form-actions">
        <button class="btn btn-primary-gradient" type="submit">
            <i class="fa-solid fa-calendar-check"></i>
            Confirm Booking
        </button>
        <a class="btn btn-soft" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            WhatsApp First
        </a>
    </div>
    <p class="form-note booking-status" role="status"></p>
</form>

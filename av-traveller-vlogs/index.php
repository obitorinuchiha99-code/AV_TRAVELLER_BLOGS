<?php
$pageTitle = 'AV Traveller Vlogs | Premium Surat Car Rental, Tours & Travel Vlogs';
$metaDescription = 'Explore Surat with AV Traveller Vlogs. Book premium cars, tour packages, travel vlogs and local guide experiences with WhatsApp booking.';
require_once __DIR__ . '/includes/header.php';

$cars = get_cars();
$places = default_places();
$packages = get_packages();
$reviews = get_reviews();
$vlogs = get_vlogs();
$gallery = gallery_images();
?>

<section class="hero-section" id="home">
    <div class="hero-bg">
        <span class="hero-slide active" style="background-image:url('https://images.unsplash.com/photo-1560422138-14c6d80287bf?auto=format&fit=crop&w=1800&q=80')"></span>
        <span class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=1800&q=80')"></span>
        <span class="hero-slide" style="background-image:url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1800&q=80')"></span>
    </div>
    <div class="container hero-content">
        <div class="hero-copy" data-aos="fade-up">
            <span class="eyebrow">Surat Car Rental · Tours · Vlogs</span>
            <h1 data-i18n="heroTitle">Explore Surat With AV Traveller Vlogs</h1>
            <p data-i18n="heroSubtitle">Travel More · Explore More · Rent Better</p>
            <div class="hero-actions">
                <a class="btn btn-primary-gradient" href="booking.php"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
                <a class="btn btn-glass" href="cars.php"><i class="fa-solid fa-car"></i> Explore Cars</a>
                <a class="btn btn-glass" href="travel-vlogs.php"><i class="fa-brands fa-youtube"></i> Watch Vlogs</a>
            </div>
        </div>
        <aside class="hero-booking glass-panel" data-aos="fade-left" data-aos-delay="150">
            <span class="panel-kicker">Instant Enquiry</span>
            <h2>Premium ride in minutes</h2>
            <p>Share your trip details and receive confirmation on WhatsApp.</p>
            <div class="hero-mini-grid">
                <span><strong class="counter" data-count="24">0</strong>/7 Support</span>
                <span><strong class="counter" data-count="6">0</strong> Fleet Types</span>
                <span><strong class="counter" data-count="8">0</strong> Surat Places</span>
            </div>
            <a class="btn btn-primary-gradient w-100" href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">Open WhatsApp</a>
        </aside>
    </div>
    <a class="scroll-indicator" href="#cars" aria-label="Scroll to cars"><span></span></a>
</section>

<section class="section-pad" id="cars">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">Live Fleet</span>
            <h2>Premium cars for every Surat route</h2>
            <p>Search, filter and book from comfortable city cars, family MPVs and premium SUVs.</p>
        </div>
        <div class="fleet-toolbar glass-panel" data-aos="fade-up">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="search" id="carSearch" placeholder="Search cars, fuel, seats">
            </div>
            <select id="carFilter" aria-label="Filter cars">
                <option value="all">All Cars</option>
                <option value="Available">Available</option>
                <option value="Booked">Booked</option>
                <option value="Maintenance">Maintenance</option>
            </select>
            <select id="carSort" aria-label="Sort cars">
                <option value="default">Sort by default</option>
                <option value="low">Price: Low to High</option>
                <option value="high">Price: High to Low</option>
            </select>
        </div>
        <div class="row g-4 car-grid">
            <?php foreach ($cars as $car): ?>
                <?php $status = (string) $car['availability_status']; ?>
                <div class="col-md-6 col-xl-4 car-item" data-name="<?= e(strtolower($car['name'] . ' ' . $car['fuel'] . ' ' . $car['seats'])) ?>" data-status="<?= e($status) ?>" data-price="<?= e((string) $car['price_per_day']) ?>" data-aos="fade-up">
                    <article class="premium-card car-card">
                        <div class="card-media">
                            <img src="<?= e($car['image_url']) ?>" alt="<?= e($car['name']) ?>" loading="lazy">
                            <span class="status-badge status-<?= e(strtolower($status)) ?>"><?= e($status) ?></span>
                        </div>
                        <div class="card-body">
                            <span class="card-kicker"><?= e($car['category']) ?></span>
                            <h3><?= e($car['name']) ?></h3>
                            <p><?= e($car['description']) ?></p>
                            <div class="spec-grid">
                                <span><i class="fa-solid fa-users"></i><?= (int) $car['seats'] ?> Seats</span>
                                <span><i class="fa-solid fa-gas-pump"></i><?= e($car['fuel']) ?></span>
                                <span><i class="fa-solid fa-gears"></i><?= e($car['transmission']) ?></span>
                            </div>
                            <div class="card-bottom">
                                <strong><?= money_inr($car['price_per_day']) ?><small>/day</small></strong>
                                <a class="btn btn-soft" href="booking.php?car=<?= (int) $car['id'] ?>">Book Now</a>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad section-band" id="places">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">Top Places In Surat</span>
            <h2>Local routes worth the window seat</h2>
            <p>Beach evenings, historic lanes, gardens, temples and family-friendly attractions.</p>
        </div>
        <div class="swiper placeSwiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($places as $place): ?>
                    <div class="swiper-slide">
                        <article class="premium-card place-card">
                            <img src="<?= e($place['image_url']) ?>" alt="<?= e($place['name']) ?>" loading="lazy">
                            <div class="card-body">
                                <h3><?= e($place['name']) ?></h3>
                                <p><?= e($place['description']) ?></p>
                                <span class="location-line"><i class="fa-solid fa-location-dot"></i><?= e($place['location']) ?></span>
                                <div class="card-actions">
                                    <a href="places.php#<?= e(strtolower(str_replace(' ', '-', $place['name']))) ?>">Read More</a>
                                    <a href="<?= e($place['maps_url']) ?>" target="_blank" rel="noopener">Google Maps</a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="section-pad booking-band" id="book">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="eyebrow">Online Booking</span>
                <h2>Plan the ride, route and payment in one flow</h2>
                <p class="lead-text">The form saves booking details to MySQL, creates a tracking code, supports Razorpay/UPI/Cash and opens WhatsApp with a confirmation message.</p>
                <div class="trust-list">
                    <span><i class="fa-solid fa-shield-halved"></i> CSRF + prepared statements</span>
                    <span><i class="fa-solid fa-receipt"></i> Invoice-ready payments</span>
                    <span><i class="fa-solid fa-calendar-days"></i> Disabled unavailable dates</span>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="glass-panel booking-panel">
                    <?php require __DIR__ . '/includes/booking-form.php'; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad" id="packages">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">Tour Packages</span>
            <h2>Weekend, family, temple and outstation plans</h2>
            <p>Curated routes with pickup, flexible stops and reliable driver support.</p>
        </div>
        <div class="row g-4">
            <?php foreach ($packages as $package): ?>
                <div class="col-md-6 col-xl-3" data-aos="fade-up">
                    <article class="premium-card package-card">
                        <img src="<?= e($package['image_url']) ?>" alt="<?= e($package['title']) ?>" loading="lazy">
                        <div class="card-body">
                            <span class="card-kicker"><?= e($package['type']) ?> · <?= e($package['duration']) ?></span>
                            <h3><?= e($package['title']) ?></h3>
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

<section class="section-pad section-band">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <span class="eyebrow">Travel Vlogs</span>
                <h2>Watch routes before you book them</h2>
                <p class="lead-text">Add YouTube URLs from admin and feature thumbnails, titles and descriptions across the site.</p>
                <a class="btn btn-primary-gradient" href="travel-vlogs.php"><i class="fa-brands fa-youtube"></i> View All Vlogs</a>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <?php foreach (array_slice($vlogs, 0, 3) as $vlog): ?>
                        <div class="col-md-4" data-aos="zoom-in">
                            <a class="vlog-tile" href="<?= e($vlog['video_url']) ?>" target="_blank" rel="noopener">
                                <img src="<?= e($vlog['thumbnail_url']) ?>" alt="<?= e($vlog['title']) ?>" loading="lazy">
                                <span><i class="fa-solid fa-play"></i></span>
                                <strong><?= e($vlog['title']) ?></strong>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">Gallery</span>
            <h2>Instagram-style moments</h2>
            <p>Responsive masonry, lazy loading and lightbox support.</p>
        </div>
        <div class="masonry-gallery">
            <?php foreach ($gallery as $item): ?>
                <a class="gallery-item glightbox" href="<?= e($item['image_url']) ?>" data-gallery="home-gallery" data-title="<?= e($item['title']) ?>" data-aos="fade-up">
                    <img src="<?= e($item['image_url']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                    <span><?= e($item['title']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad reviews-section">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">Customer Reviews</span>
            <h2>Trusted by local families and travellers</h2>
        </div>
        <div class="swiper reviewSwiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach ($reviews as $review): ?>
                    <div class="swiper-slide">
                        <article class="review-card glass-panel">
                            <div class="stars"><?= str_repeat('<i class="fa-solid fa-star"></i>', (int) $review['rating']) ?></div>
                            <p>“<?= e($review['message']) ?>”</p>
                            <div class="review-person">
                                <img src="<?= e($review['photo_url']) ?>" alt="<?= e($review['name']) ?>" loading="lazy">
                                <strong><?= e($review['name']) ?></strong>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<section class="section-pad map-section" id="contact">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="glass-panel h-100">
                    <span class="eyebrow">Contact</span>
                    <h2>Start from Surat, go anywhere</h2>
                    <p class="lead-text">Call, WhatsApp or use Google Maps navigation for pickup planning.</p>
                    <div class="contact-list">
                        <a href="tel:<?= e(PRIMARY_PHONE) ?>"><i class="fa-solid fa-phone"></i><?= e(PRIMARY_PHONE) ?></a>
                        <a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i><?= e(SECONDARY_PHONE) ?></a>
                        <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><i class="fa-solid fa-envelope"></i><?= e(CONTACT_EMAIL) ?></a>
                        <a href="<?= e(GOOGLE_MAPS_ROUTE) ?>" target="_blank" rel="noopener"><i class="fa-solid fa-route"></i>Open Route</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <iframe class="map-frame" src="<?= e(GOOGLE_MAPS_EMBED) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="AV Traveller Vlogs Surat map"></iframe>
            </div>
        </div>
    </div>
</section>

<section class="section-pad faq-section">
    <div class="container">
        <div class="section-heading" data-aos="fade-up">
            <span class="eyebrow">FAQ</span>
            <h2>Rental questions, answered fast</h2>
        </div>
        <div class="accordion premium-accordion" id="faqAccordion" data-aos="fade-up">
            <?php
            $faqs = [
                ['Do you provide cars with drivers?', 'Yes. Driver-required bookings are supported and recommended for outstation, wedding and full-day tour plans.'],
                ['Can I pay online?', 'Yes. The project includes Razorpay order creation, UPI QR and cash-on-pickup structures. Add live Razorpay keys in config for production.'],
                ['How does live availability work?', 'Admin can mark cars as Available, Booked or Maintenance. Calendar unavailable dates are returned by the availability API.'],
            ];
            foreach ($faqs as $index => $faq):
            ?>
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button <?= $index === 0 ? '' : 'collapsed' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $index ?>" aria-expanded="<?= $index === 0 ? 'true' : 'false' ?>">
                            <?= e($faq[0]) ?>
                        </button>
                    </h3>
                    <div id="faq<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#faqAccordion">
                        <div class="accordion-body"><?= e($faq[1]) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
$extraScript = 'assets/js/booking.js';
require_once __DIR__ . '/includes/footer.php';
?>

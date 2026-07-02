# AV Traveller Vlogs

Production-ready PHP 8 + MySQL website for a premium Surat car rental, tour package and travel vlog business.

## Included

- Public website with Bootstrap 5, jQuery, AOS, Swiper, GSAP, Font Awesome, GLightbox and Flatpickr.
- Premium glassmorphism dark/light UI, mobile navigation, floating WhatsApp/call buttons and smooth animations.
- Online booking with MySQL save, booking code, live availability dates, rental calculator and WhatsApp confirmation.
- Payment structure for Razorpay, UPI QR and cash on pickup.
- Secure admin panel for cars, bookings, availability, gallery, reviews, packages, vlogs, customers and CSV export.
- PWA manifest, service worker, offline page, favicon, install icons, robots, sitemap and Apache hardening.
- MySQL schema and seed data in `database/travel_booking.sql`.

## Install

1. Upload this folder to a PHP 8+ hosting environment.
2. Create/import the database using `database/travel_booking.sql`.
3. Update `config/config.php` or environment variables:
   - `APP_URL`
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`
   - `RAZORPAY_KEY_ID`
   - `RAZORPAY_KEY_SECRET`
4. Ensure `uploads/` is writable by PHP.
5. Open `/admin/login.php`.

Default seed admin:

- Email: `admin@avtravellervlogs.com`
- Password: `password`

Change the default admin password immediately after deployment.

## Image Sources

The seeded images use royalty-free remote images from Unsplash via `images.unsplash.com` URLs. Replace any representative fleet photo with exact owned/licensed vehicle photos from the real AV Traveller Vlogs fleet before launch.

## Security Notes

- Database writes use PDO prepared statements.
- Admin passwords use PHP `password_hash`/`password_verify`.
- Public and admin forms include CSRF tokens.
- Output is escaped with `htmlspecialchars`.
- Uploads validate MIME type and size.
- `.htaccess` disables directory listing and adds common security headers.

## Production Checklist

- Verify the Instagram/Facebook/YouTube links in `config/config.php` before final launch.
- Add more AV Traveller Vlogs videos from admin as the YouTube library grows.
- Replace UPI ID in `includes/booking-form.php` with the business UPI ID.
- Add live Razorpay keys and configure the webhook URL `/api/razorpay-webhook.php`.
- Update `sitemap.xml` and `robots.txt` with the final domain.
- Run Lighthouse after hosting, then compress any uploaded images to WebP.

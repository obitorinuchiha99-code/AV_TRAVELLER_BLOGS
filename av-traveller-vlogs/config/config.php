<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

define('APP_NAME', 'AV Traveller Vlogs');
define('APP_TAGLINE', 'Travel More. Explore More. Rent Better.');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost/av-traveller-vlogs');

define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
define('DB_NAME', getenv('DB_NAME') ?: 'travel_booking');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

define('PRIMARY_PHONE', '+919512099573');
define('SECONDARY_PHONE', '+917875933336');
define('PRIMARY_WHATSAPP', '919512099573');
define('SECONDARY_WHATSAPP', '917875933336');
define('CONTACT_EMAIL', 'booking@avtravellervlogs.com');

define('GOOGLE_MAPS_EMBED', 'https://www.google.com/maps?q=Surat,%20Gujarat,%20India&output=embed');
define('GOOGLE_MAPS_ROUTE', 'https://www.google.com/maps/dir/?api=1&destination=Surat,Gujarat,India');

define('INSTAGRAM_URL', 'https://www.instagram.com/av_traveller_vlogs/');
define('FACEBOOK_URL', 'https://www.facebook.com/story.php?story_fbid=836024155187377&id=100064385070537');
define('YOUTUBE_URL', 'https://www.youtube.com/shorts/3-3uD-6hlnE');

define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_test_replace_with_live_key');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'replace_with_live_secret');
define('RAZORPAY_ENABLED', (bool) getenv('RAZORPAY_KEY_SECRET'));

define('CSRF_KEY', 'av_csrf_token');
define('ADMIN_SESSION_KEY', 'av_admin_user');

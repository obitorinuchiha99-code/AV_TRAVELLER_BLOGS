<?php
declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function default_cars(): array
{
    return [
        [
            'id' => 1,
            'name' => 'Toyota Innova Crysta',
            'slug' => 'toyota-innova-crysta',
            'category' => 'Premium MPV',
            'image_url' => 'https://images.unsplash.com/photo-1669011950339-a89f75b7a415?auto=format&fit=crop&w=1200&q=80',
            'seats' => 7,
            'fuel' => 'Diesel',
            'transmission' => 'Manual / Automatic',
            'price_per_day' => 4200,
            'availability_status' => 'Available',
            'description' => 'A spacious family favourite for Surat city rides, airport transfers, weddings and outstation tours.',
        ],
        [
            'id' => 2,
            'name' => 'Toyota Fortuner',
            'slug' => 'toyota-fortuner',
            'category' => 'Luxury SUV',
            'image_url' => 'https://images.unsplash.com/photo-1654586761333-1763cb9ee0db?auto=format&fit=crop&w=1200&q=80',
            'seats' => 7,
            'fuel' => 'Diesel',
            'transmission' => 'Automatic',
            'price_per_day' => 7500,
            'availability_status' => 'Available',
            'description' => 'Commanding SUV comfort for premium business travel, highway routes and VIP tour plans.',
        ],
        [
            'id' => 3,
            'name' => 'Kia Carnival',
            'slug' => 'kia-carnival',
            'category' => 'Executive MPV',
            'image_url' => 'https://images.unsplash.com/photo-1621993202323-f438eec934d0?auto=format&fit=crop&w=1200&q=80',
            'seats' => 7,
            'fuel' => 'Diesel',
            'transmission' => 'Automatic',
            'price_per_day' => 8500,
            'availability_status' => 'Booked',
            'description' => 'Business-class seating and a quiet cabin for family events, corporate pickup and long-distance comfort.',
        ],
        [
            'id' => 4,
            'name' => 'Maruti Ertiga',
            'slug' => 'maruti-ertiga',
            'category' => 'Budget MPV',
            'image_url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1200&q=80',
            'seats' => 7,
            'fuel' => 'Petrol / CNG',
            'transmission' => 'Manual',
            'price_per_day' => 2800,
            'availability_status' => 'Available',
            'description' => 'Efficient seven-seater option for daily rentals, pilgrim trips and compact family travel.',
        ],
        [
            'id' => 5,
            'name' => 'Hyundai Creta',
            'slug' => 'hyundai-creta',
            'category' => 'Compact SUV',
            'image_url' => 'https://images.unsplash.com/photo-1662980263070-bd73cbf4f6e0?auto=format&fit=crop&w=1200&q=80',
            'seats' => 5,
            'fuel' => 'Petrol / Diesel',
            'transmission' => 'Manual / Automatic',
            'price_per_day' => 3500,
            'availability_status' => 'Available',
            'description' => 'Stylish city SUV for couples, small families and smooth local sightseeing days.',
        ],
        [
            'id' => 6,
            'name' => 'Mahindra XUV700',
            'slug' => 'mahindra-xuv700',
            'category' => 'Premium SUV',
            'image_url' => 'https://images.unsplash.com/photo-1617469767053-d3b523a0b982?auto=format&fit=crop&w=1200&q=80',
            'seats' => 7,
            'fuel' => 'Diesel',
            'transmission' => 'Automatic',
            'price_per_day' => 6200,
            'availability_status' => 'Maintenance',
            'description' => 'Modern SUV with strong highway presence for outstation tours and luxury group travel.',
        ],
    ];
}

function get_cars(): array
{
    $cars = db_all('SELECT * FROM cars WHERE is_active = 1 ORDER BY sort_order, id');
    return $cars ?: default_cars();
}

function default_places(): array
{
    return [
        [
            'name' => 'Dumas Beach',
            'image_url' => 'https://images.unsplash.com/photo-1560422138-14c6d80287bf?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Dumas Road, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Dumas+Beach+Surat',
            'description' => 'A breezy Arabian Sea escape known for black sand, sunset views and relaxed evening food stalls.',
        ],
        [
            'name' => 'Surat Castle',
            'image_url' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Chowk Bazaar, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Surat+Castle',
            'description' => 'A restored 16th-century fort that tells the story of Surat as one of India’s great trading cities.',
        ],
        [
            'name' => 'Science Centre',
            'image_url' => 'https://images.unsplash.com/photo-1581090700227-1e37b190418e?auto=format&fit=crop&w=1200&q=80',
            'location' => 'City Light Road, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Science+Centre+Surat',
            'description' => 'A family-friendly museum and learning stop with science galleries, shows and interactive exhibits.',
        ],
        [
            'name' => 'ISKCON Temple',
            'image_url' => 'https://images.unsplash.com/photo-1605379399843-5870eea9b74e?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Jahangirpura, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=ISKCON+Temple+Surat',
            'description' => 'A peaceful devotional destination for darshan, spiritual music and calm family visits.',
        ],
        [
            'name' => 'Ambaji Temple',
            'image_url' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Surat, Gujarat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Ambaji+Temple+Surat',
            'description' => 'A loved temple stop often added to family and devotional routes around Surat.',
        ],
        [
            'name' => 'Dutch Garden',
            'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Nanpura, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Dutch+Garden+Surat',
            'description' => 'Historic riverside gardens with colonial-era tombs, walking paths and quiet green corners.',
        ],
        [
            'name' => 'Gopi Talav',
            'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Rustampura, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Gopi+Talav+Surat',
            'description' => 'A lively lakefront recreation area with boating, evening lights and family attractions.',
        ],
        [
            'name' => 'Sarthana Nature Park',
            'image_url' => 'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=1200&q=80',
            'location' => 'Sarthana Jakat Naka, Surat',
            'maps_url' => 'https://www.google.com/maps/search/?api=1&query=Sarthana+Nature+Park+Surat',
            'description' => 'A popular zoo and green day-out spot for children, nature walks and relaxed sightseeing.',
        ],
    ];
}

function default_packages(): array
{
    return [
        ['id' => 1, 'title' => 'Weekend Surat Escape', 'type' => 'Weekend', 'duration' => '2 Days / 1 Night', 'price' => 6999, 'image_url' => 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1200&q=80', 'description' => 'Dumas, Dutch Garden, Gopi Talav, food trail and night drive with comfortable pickup.'],
        ['id' => 2, 'title' => 'Family Comfort Tour', 'type' => 'Family', 'duration' => '1 Day', 'price' => 4999, 'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80', 'description' => 'Doorstep pickup, child-friendly places, flexible stops and a polished chauffeur experience.'],
        ['id' => 3, 'title' => 'Temple Darshan Route', 'type' => 'Temple', 'duration' => '1 Day', 'price' => 3999, 'image_url' => 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1200&q=80', 'description' => 'ISKCON, Ambaji and nearby devotional stops with morning pickup and route guidance.'],
        ['id' => 4, 'title' => 'Outstation Luxury Drive', 'type' => 'Outstation', 'duration' => 'Custom', 'price' => 9999, 'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'description' => 'Premium car with driver for Saputara, Statue of Unity, Mumbai, Udaipur or custom routes.'],
    ];
}

function get_packages(): array
{
    $packages = db_all('SELECT * FROM tour_packages WHERE is_active = 1 ORDER BY id DESC');
    return $packages ?: default_packages();
}

function default_reviews(): array
{
    return [
        ['name' => 'Rohan Patel', 'photo_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80', 'rating' => 5, 'message' => 'Clean Innova, punctual driver and a smooth Dumas Beach evening plan. Booking on WhatsApp was instant.'],
        ['name' => 'Neha Shah', 'photo_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80', 'rating' => 5, 'message' => 'The Fortuner felt premium and safe for our outstation family trip. Transparent pricing and polite service.'],
        ['name' => 'Amit Mehta', 'photo_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80', 'rating' => 5, 'message' => 'Loved the vlog-style recommendations. They know Surat food stops and photo points very well.'],
    ];
}

function get_reviews(): array
{
    $reviews = db_all('SELECT * FROM reviews WHERE is_active = 1 ORDER BY id DESC LIMIT 12');
    return $reviews ?: default_reviews();
}

function default_vlogs(): array
{
    return [
        ['title' => 'AV Traveller Vlogs Road Short', 'video_url' => 'https://www.youtube.com/shorts/3-3uD-6hlnE', 'thumbnail_url' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=1200&q=80', 'description' => 'A public AV Traveller Vlogs short for quick travel inspiration and route discovery.'],
        ['title' => 'AV Traveller Vlogs Car Experience', 'video_url' => 'https://www.youtube.com/watch?v=UxdQrN7QeyY', 'thumbnail_url' => 'https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&w=1200&q=80', 'description' => 'A public AV Traveller Vlogs video reference for the rental and travel audience.'],
        ['title' => 'Surat Night Drive And Food Stops', 'video_url' => YOUTUBE_URL, 'thumbnail_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'description' => 'Night routes, family-friendly food zones and safe late-evening drive ideas.'],
    ];
}

function get_vlogs(): array
{
    $vlogs = db_all('SELECT * FROM travel_vlogs WHERE is_active = 1 ORDER BY published_at DESC, id DESC');
    return $vlogs ?: default_vlogs();
}

function gallery_images(): array
{
    $gallery = db_all('SELECT * FROM gallery WHERE is_active = 1 ORDER BY sort_order, id DESC');
    if ($gallery) {
        return $gallery;
    }

    return [
        ['title' => 'Surat Heritage', 'image_url' => 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Beach Evening', 'image_url' => 'https://images.unsplash.com/photo-1560422138-14c6d80287bf?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Luxury Drive', 'image_url' => 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Family Tour', 'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Road Trip', 'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80'],
        ['title' => 'Premium Fleet', 'image_url' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80'],
    ];
}

CREATE DATABASE IF NOT EXISTS travel_booking
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE travel_booking;

CREATE TABLE admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('super_admin', 'manager') NOT NULL DEFAULT 'manager',
  last_login_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cars (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  slug VARCHAR(190) NOT NULL UNIQUE,
  category VARCHAR(120) NOT NULL,
  image_url VARCHAR(700) NOT NULL,
  seats TINYINT UNSIGNED NOT NULL,
  fuel VARCHAR(80) NOT NULL,
  transmission VARCHAR(120) NOT NULL,
  price_per_day DECIMAL(10,2) NOT NULL,
  availability_status ENUM('Available', 'Booked', 'Maintenance') NOT NULL DEFAULT 'Available',
  description TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT UNSIGNED NOT NULL DEFAULT 99,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_cars_status (availability_status),
  INDEX idx_cars_price (price_per_day)
) ENGINE=InnoDB;

CREATE TABLE customers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  whatsapp VARCHAR(30) NOT NULL,
  email VARCHAR(180) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_customer_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_code VARCHAR(40) NOT NULL UNIQUE,
  customer_id INT UNSIGNED NULL,
  customer_name VARCHAR(160) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  whatsapp VARCHAR(30) NOT NULL,
  pickup_date DATE NOT NULL,
  return_date DATE NOT NULL,
  pickup_location VARCHAR(255) NOT NULL,
  drop_location VARCHAR(255) NOT NULL,
  car_id INT UNSIGNED NULL,
  car_name VARCHAR(160) NOT NULL,
  driver_required TINYINT(1) NOT NULL DEFAULT 0,
  message TEXT NULL,
  estimated_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  status ENUM('Pending', 'Confirmed', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
  payment_status ENUM('Unpaid', 'Pending', 'Paid', 'Failed', 'Refunded') NOT NULL DEFAULT 'Unpaid',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_bookings_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
  CONSTRAINT fk_bookings_car FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE SET NULL,
  INDEX idx_booking_dates (pickup_date, return_date),
  INDEX idx_booking_status (status),
  INDEX idx_booking_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE gallery (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  image_url VARCHAR(700) NOT NULL,
  alt_text VARCHAR(190) NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 99,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE reviews (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  photo_url VARCHAR(700) NOT NULL,
  rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
  message TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE tour_packages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  type VARCHAR(80) NOT NULL,
  duration VARCHAR(80) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(700) NOT NULL,
  description TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE travel_vlogs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  video_url VARCHAR(700) NOT NULL,
  thumbnail_url VARCHAR(700) NOT NULL,
  description TEXT NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  email VARCHAR(180) NULL,
  subject VARCHAR(180) NULL,
  message TEXT NOT NULL,
  status ENUM('New', 'Read', 'Closed') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_id INT UNSIGNED NULL,
  booking_code VARCHAR(40) NOT NULL,
  provider ENUM('Razorpay', 'UPI', 'Cash') NOT NULL DEFAULT 'Razorpay',
  razorpay_order_id VARCHAR(120) NULL,
  razorpay_payment_id VARCHAR(120) NULL,
  razorpay_signature VARCHAR(255) NULL,
  amount DECIMAL(10,2) NOT NULL,
  currency CHAR(3) NOT NULL DEFAULT 'INR',
  status ENUM('Created', 'Success', 'Failed', 'Cash Pending') NOT NULL DEFAULT 'Created',
  paid_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE SET NULL,
  INDEX idx_payments_booking_code (booking_code)
) ENGINE=InnoDB;

CREATE TABLE availability (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  car_id INT UNSIGNED NOT NULL,
  unavailable_date DATE NOT NULL,
  status ENUM('Booked', 'Maintenance') NOT NULL DEFAULT 'Booked',
  note VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_availability_car FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
  UNIQUE KEY uniq_car_date (car_id, unavailable_date),
  INDEX idx_availability_date (unavailable_date)
) ENGINE=InnoDB;

CREATE TABLE newsletter_subscribers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(180) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admin_notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  message TEXT NOT NULL,
  type ENUM('Booking', 'Payment', 'Contact', 'System') NOT NULL DEFAULT 'System',
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admins (name, email, password_hash, role)
VALUES
  ('AV Admin', 'admin@avtravellervlogs.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi.', 'super_admin');

INSERT INTO cars (name, slug, category, image_url, seats, fuel, transmission, price_per_day, availability_status, description, sort_order)
VALUES
  ('Toyota Innova Crysta', 'toyota-innova-crysta', 'Premium MPV', 'https://images.unsplash.com/photo-1669011950339-a89f75b7a415?auto=format&fit=crop&w=1200&q=80', 7, 'Diesel', 'Manual / Automatic', 4200, 'Available', 'A spacious family favourite for Surat city rides, airport transfers, weddings and outstation tours.', 1),
  ('Toyota Fortuner', 'toyota-fortuner', 'Luxury SUV', 'https://images.unsplash.com/photo-1654586761333-1763cb9ee0db?auto=format&fit=crop&w=1200&q=80', 7, 'Diesel', 'Automatic', 7500, 'Available', 'Commanding SUV comfort for premium business travel, highway routes and VIP tour plans.', 2),
  ('Kia Carnival', 'kia-carnival', 'Executive MPV', 'https://images.unsplash.com/photo-1621993202323-f438eec934d0?auto=format&fit=crop&w=1200&q=80', 7, 'Diesel', 'Automatic', 8500, 'Booked', 'Business-class seating and a quiet cabin for family events, corporate pickup and long-distance comfort.', 3),
  ('Maruti Ertiga', 'maruti-ertiga', 'Budget MPV', 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1200&q=80', 7, 'Petrol / CNG', 'Manual', 2800, 'Available', 'Efficient seven-seater option for daily rentals, pilgrim trips and compact family travel.', 4),
  ('Hyundai Creta', 'hyundai-creta', 'Compact SUV', 'https://images.unsplash.com/photo-1662980263070-bd73cbf4f6e0?auto=format&fit=crop&w=1200&q=80', 5, 'Petrol / Diesel', 'Manual / Automatic', 3500, 'Available', 'Stylish city SUV for couples, small families and smooth local sightseeing days.', 5),
  ('Mahindra XUV700', 'mahindra-xuv700', 'Premium SUV', 'https://images.unsplash.com/photo-1617469767053-d3b523a0b982?auto=format&fit=crop&w=1200&q=80', 7, 'Diesel', 'Automatic', 6200, 'Maintenance', 'Modern SUV with strong highway presence for outstation tours and luxury group travel.', 6);

INSERT INTO gallery (title, image_url, alt_text, sort_order)
VALUES
  ('Surat Heritage', 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=900&q=80', 'Surat heritage architecture', 1),
  ('Beach Evening', 'https://images.unsplash.com/photo-1560422138-14c6d80287bf?auto=format&fit=crop&w=900&q=80', 'Surat beach evening', 2),
  ('Luxury Drive', 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=900&q=80', 'Luxury rental car on the road', 3),
  ('Family Tour', 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=900&q=80', 'Family travel destination', 4),
  ('Road Trip', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80', 'Scenic road trip', 5),
  ('Premium Fleet', 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=900&q=80', 'Premium car fleet', 6);

INSERT INTO reviews (name, photo_url, rating, message)
VALUES
  ('Rohan Patel', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80', 5, 'Clean Innova, punctual driver and a smooth Dumas Beach evening plan. Booking on WhatsApp was instant.'),
  ('Neha Shah', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80', 5, 'The Fortuner felt premium and safe for our outstation family trip. Transparent pricing and polite service.'),
  ('Amit Mehta', 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=300&q=80', 5, 'Loved the vlog-style recommendations. They know Surat food stops and photo points very well.');

INSERT INTO tour_packages (title, type, duration, price, image_url, description)
VALUES
  ('Weekend Surat Escape', 'Weekend', '2 Days / 1 Night', 6999, 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1200&q=80', 'Dumas, Dutch Garden, Gopi Talav, food trail and night drive with comfortable pickup.'),
  ('Family Comfort Tour', 'Family', '1 Day', 4999, 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80', 'Doorstep pickup, child-friendly places, flexible stops and a polished chauffeur experience.'),
  ('Temple Darshan Route', 'Temple', '1 Day', 3999, 'https://images.unsplash.com/photo-1524492412937-b28074a5d7da?auto=format&fit=crop&w=1200&q=80', 'ISKCON, Ambaji and nearby devotional stops with morning pickup and route guidance.'),
  ('Outstation Luxury Drive', 'Outstation', 'Custom', 9999, 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'Premium car with driver for Saputara, Statue of Unity, Mumbai, Udaipur or custom routes.');

INSERT INTO travel_vlogs (title, video_url, thumbnail_url, description, published_at)
VALUES
  ('AV Traveller Vlogs Road Short', 'https://www.youtube.com/shorts/3-3uD-6hlnE', 'https://images.unsplash.com/photo-1589308078059-be1415eab4c3?auto=format&fit=crop&w=1200&q=80', 'A public AV Traveller Vlogs short for quick travel inspiration and route discovery.', NOW()),
  ('AV Traveller Vlogs Car Experience', 'https://www.youtube.com/watch?v=UxdQrN7QeyY', 'https://images.unsplash.com/photo-1542362567-b07e54358753?auto=format&fit=crop&w=1200&q=80', 'A public AV Traveller Vlogs video reference for the rental and travel audience.', NOW()),
  ('Surat Night Drive And Food Stops', 'https://www.youtube.com/shorts/3-3uD-6hlnE', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=80', 'Night routes, family-friendly food zones and safe late-evening drive ideas.', NOW());

INSERT INTO availability (car_id, unavailable_date, status, note)
VALUES
  (3, DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY), 'Booked', 'Wedding booking'),
  (3, DATE_ADD(CURRENT_DATE, INTERVAL 3 DAY), 'Booked', 'Wedding booking'),
  (6, DATE_ADD(CURRENT_DATE, INTERVAL 1 DAY), 'Maintenance', 'Scheduled service');

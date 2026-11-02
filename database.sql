-- =========================================================
-- CarZone — قاعدة البيانات (database.sql)
-- طريقة الاستيراد: افتح phpMyAdmin ← Import ← اختر هذا الملف ← Go
-- الملف ينشئ قاعدة البيانات carzone وكل الجداول والبيانات الأولية.
-- تنبيه: تشغيله مرة ثانية يحذف الجداول القديمة ويعيد إنشاءها.
-- =========================================================

CREATE DATABASE IF NOT EXISTS carzone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carzone;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS car_images, cars, dealers, plans, messages, users;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------
-- 1) users: كل الحسابات (عميل / صاحب معرض / مدير)
-- ---------------------------------------------------------
CREATE TABLE users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,                 -- كلمة المرور مشفرة بـ password_hash
  role          ENUM('customer','dealer','admin') NOT NULL DEFAULT 'customer',
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 2) plans: باقات الاشتراك لأصحاب المعارض
-- ---------------------------------------------------------
CREATE TABLE plans (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name         VARCHAR(50)  NOT NULL,                  -- الاسم بالإنجليزي (Basic / Pro / Premium)
  name_ar      VARCHAR(50)  NOT NULL,                  -- الاسم بالعربي
  price        DECIMAL(12,0) NOT NULL DEFAULT 0,       -- السعر الشهري بالريال اليمني (0 = لم يحدد بعد)
  max_listings INT UNSIGNED NOT NULL DEFAULT 0,        -- أقصى عدد سيارات معروضة
  features     TEXT                                    -- المميزات (كل ميزة في سطر)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 3) dealers: بيانات المعارض (كل معرض مربوط بحساب في users)
-- ---------------------------------------------------------
CREATE TABLE dealers (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id       INT UNSIGNED NOT NULL UNIQUE,          -- صاحب المعرض (users.id)
  showroom_name VARCHAR(120) NOT NULL,
  address       VARCHAR(255) NOT NULL,
  phone         VARCHAR(30)  NOT NULL,
  license_image VARCHAR(255) NULL,                     -- مسار صورة الترخيص داخل uploads/
  status        ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  plan_id       INT UNSIGNED NULL,                     -- الباقة المختارة (plans.id)
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_dealers_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_dealers_plan FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 4) cars: السيارات (كل سيارة تابعة لمعرض)
-- ---------------------------------------------------------
CREATE TABLE cars (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  dealer_id    INT UNSIGNED NOT NULL,                  -- المعرض (dealers.id)
  title        VARCHAR(150) NOT NULL,
  brand        VARCHAR(60)  NULL,
  model        VARCHAR(60)  NULL,
  year         SMALLINT UNSIGNED NULL,
  `condition`  ENUM('new','used') NOT NULL,            -- جديدة / مستعملة
  type         ENUM('sedan','suv','hatchback','luxury') NOT NULL,
  price        DECIMAL(14,0) NULL,                     -- السعر بالريال اليمني
  mileage      INT UNSIGNED NULL,                      -- الممشى بالكيلومتر
  fuel         VARCHAR(30)  NULL,
  transmission VARCHAR(30)  NULL,
  color        VARCHAR(30)  NULL,
  city         VARCHAR(60)  NULL,
  description  TEXT NULL,
  is_sample    TINYINT(1) NOT NULL DEFAULT 0,          -- 1 = إعلان تجريبي
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_cars_filter (`condition`, type),
  CONSTRAINT fk_cars_dealer FOREIGN KEY (dealer_id) REFERENCES dealers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 5) car_images: صور السيارات (السيارة الواحدة لها أكثر من صورة)
-- ---------------------------------------------------------
CREATE TABLE car_images (
  id     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  car_id INT UNSIGNED NOT NULL,                        -- السيارة (cars.id)
  path   VARCHAR(255) NOT NULL,                        -- مسار الصورة (images/ أو uploads/cars/)
  CONSTRAINT fk_images_car FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 6) messages: رسائل نموذج "تواصل معنا"
-- ---------------------------------------------------------
CREATE TABLE messages (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  phone      VARCHAR(30)  NOT NULL,
  subject    VARCHAR(100) NULL,
  message    TEXT NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- البيانات الأولية
-- =========================================================

-- حساب المدير: اسم المستخدم admin وكلمة المرور admin123
-- ⚠ غيّر كلمة المرور بعد أول دخول (أو أنشئ hash جديد بـ password_hash في PHP)
INSERT INTO users (id, username, email, password_hash, role) VALUES
(1, 'admin', 'admin@carzone.local', '$2y$10$llU3EZrqKuW1BKsqQYEQQeE4/Am3VhtglbDzDJMDLU3FIqUYvOd5C', 'admin');

-- حساب صاحب معرض تجريبي (مقبول) تتبعه السيارات التجريبية
-- الدخول: demo_dealer / dealer123
INSERT INTO users (id, username, email, password_hash, role) VALUES
(2, 'demo_dealer', 'dealer@carzone.local', '$2y$10$KrfF8bskZxMXi6k9x/JePuhEr96knfGZtn41GYrf5S7Tgh8564FO.', 'dealer');

-- الباقات — EDIT: الأسعار (0 = لم تحدد بعد) وعدد السيارات قيم مؤقتة، غيّرها هنا أو من لوحة الأدمن
INSERT INTO plans (id, name, name_ar, price, max_listings, features) VALUES
(1, 'Basic',   'أساسية',   0, 5,  'صفحة للمعرض\nتواصل واتساب مباشر'),
(2, 'Pro',     'احترافية', 0, 20, 'صفحة للمعرض\nظهور أعلى في القائمة\nتواصل واتساب مباشر'),
(3, 'Premium', 'مميزة',    0, 50, 'صفحة للمعرض\nظهور في السيارات المميزة\nدعم أولوية');

INSERT INTO dealers (id, user_id, showroom_name, address, phone, license_image, status, plan_id) VALUES
(1, 2, 'معرض CarZone التجريبي', 'صنعاء', '+967 7XX XXX XXX', NULL, 'approved', 2);

-- 12 سيارة تجريبية (is_sample = 1) — السعر والممشى فارغة حتى تعدّلها
INSERT INTO cars (id, dealer_id, title, brand, model, year, `condition`, type, price, mileage, fuel, transmission, color, city, description, is_sample) VALUES
(1, 1, 'تويوتا كورولا 2022', 'Toyota', 'Corolla', 2022, 'new', 'sedan', NULL, NULL, 'بنزين', 'أوتوماتيك', 'أبيض', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(2, 1, 'هوندا سيفيك 2021', 'Honda', 'Civic', 2021, 'used', 'sedan', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(3, 1, 'هوندا سيتي 2022', 'Honda', 'City', 2022, 'new', 'sedan', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(4, 1, 'ميتسوبيشي باجيرو سبورت 2021', 'Mitsubishi', 'Pajero Sport', 2021, 'new', 'suv', NULL, NULL, 'ديزل', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(5, 1, 'فولكس فاجن طوارق 2022', 'Volkswagen', 'Touareg', 2022, 'new', 'suv', NULL, NULL, 'بنزين', 'أوتوماتيك', 'أسود', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(6, 1, 'تويوتا إف جي كروزر 2020', 'Toyota', 'FJ Cruiser', 2020, 'used', 'suv', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(7, 1, 'سوزوكي سويفت 2022', 'Suzuki', 'Swift', 2022, 'new', 'hatchback', NULL, NULL, 'بنزين', 'يدوي', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(8, 1, 'فولكس فاجن جولف 2018', 'Volkswagen', 'Golf', 2018, 'used', 'hatchback', NULL, NULL, 'بنزين', 'أوتوماتيك', 'أزرق', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(9, 1, 'فورد فوكس 2020', 'Ford', 'Focus', 2020, 'used', 'hatchback', NULL, NULL, 'بنزين', 'أوتوماتيك', 'أسود', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(10, 1, 'مرسيدس بنز E-Class', 'Mercedes-Benz', 'E-Class', NULL, 'used', 'luxury', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(11, 1, 'رينج روفر سبورت 2020', 'Land Rover', 'Range Rover Sport', 2020, 'used', 'luxury', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1),
(12, 1, 'جينيسيس GV80 2022', 'Genesis', 'GV80', 2022, 'new', 'luxury', NULL, NULL, 'بنزين', 'أوتوماتيك', '—', 'صنعاء', 'إعلان تجريبي. اكتب هنا وصف السيارة: الحالة العامة، سجل الصيانة، عدد الملاك، والإضافات الموجودة فيها.', 1);

INSERT INTO car_images (car_id, path) VALUES
(1, 'images/car-corolla.jpg'),
(1, 'images/car-corolla-2.jpg'),
(1, 'images/service-interior.jpg'),
(2, 'images/car-civic.jpg'),
(3, 'images/car-city.jpg'),
(4, 'images/car-fortuner.jpg'),
(5, 'images/car-suv-black.jpg'),
(6, 'images/car-fj.jpg'),
(7, 'images/car-swift.jpg'),
(8, 'images/car-hatch-blue.jpg'),
(9, 'images/car-hatch-black.jpg'),
(10, 'images/car-mercedes.jpg'),
(11, 'images/car-rangerover.jpg'),
(12, 'images/car-lux-suv.jpg');

<?php
/* =========================================================
   CarZone — إعدادات المشروع (config.php)
   غيّر بيانات قاعدة البيانات هنا فقط.
   القيم الافتراضية تناسب XAMPP: localhost / root / بدون كلمة مرور
   ========================================================= */

define('DB_HOST', 'localhost');   // EDIT: عنوان خادم قاعدة البيانات
define('DB_NAME', 'carzone');     // EDIT: اسم قاعدة البيانات
define('DB_USER', 'root');        // EDIT: اسم مستخدم MySQL
define('DB_PASS', '');            // EDIT: كلمة مرور MySQL (فارغة في XAMPP)

define('UPLOAD_MAX_BYTES', 3 * 1024 * 1024);   // أقصى حجم للصورة المرفوعة: 3 ميجابايت
define('CURRENCY', 'ريال يمني');

// إظهار الأخطاء أثناء التطوير (اجعلها 0 عند النشر)
ini_set('display_errors', '1');
error_reporting(E_ALL);

// بدء الجلسة (Session) لتسجيل الدخول
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/functions.php';

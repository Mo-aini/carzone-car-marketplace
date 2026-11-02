<?php
/* =========================================================
   db.php — الاتصال بقاعدة البيانات عن طريق PDO
   تُستدعى الدالة db() من أي صفحة للحصول على الاتصال.
   ========================================================= */

function db(): PDO
{
    static $pdo = null;               // اتصال واحد فقط لكل طلب
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // أي خطأ = استثناء
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // النتائج كمصفوفة
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Prepared statements حقيقية
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('<div dir="rtl" style="font-family:Tahoma;padding:30px;background:#060d19;color:#eaf1fb;min-height:100vh">'
               . '<h2>تعذّر الاتصال بقاعدة البيانات</h2>'
               . '<p>تأكد أن MySQL شغّال في XAMPP، وأنك استوردت الملف database.sql، وأن بيانات الاتصال في config.php صحيحة.</p>'
               . '<p style="color:#9fb1c9;direction:ltr;text-align:left">' . htmlspecialchars($e->getMessage()) . '</p></div>');
        }
    }
    return $pdo;
}

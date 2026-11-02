<?php
/* تبويبات لوحة الأدمن. $adminPrefix = '' داخل admin/ أو '../admin/' من مجلد آخر */
$ap = $adminPrefix ?? '';
$tabs = [
    $ap . 'index.php'    => 'الإحصائيات',
    $ap . 'dealers.php'  => 'طلبات المعارض',
    $ap . 'cars.php'     => 'السيارات',
    $ap . 'users.php'    => 'المستخدمون',
    $ap . 'plans.php'    => 'الباقات',
    $ap . 'messages.php' => 'الرسائل',
    $ap . '../logout.php'=> 'تسجيل الخروج',
];

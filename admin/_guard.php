<?php
/* _guard.php — يُستدعى أول كل صفحة أدمن: الاتصال + التأكد أن المستخدم مدير */
require __DIR__ . '/../config.php';
require_role('admin', 'login.php');
$base = '../';
require __DIR__ . '/_tabs.php';

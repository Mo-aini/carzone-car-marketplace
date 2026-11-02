<?php
/* head.php — بداية كل صفحة (<head>).
   قبل استدعائها عرّف: $title, $desc, $bodyClass, و $base ('' للجذر أو '../' للمجلدات الفرعية) */
$base      = $base ?? '';
$title     = $title ?? 'CarZone — Drive Your Next';
$desc      = $desc ?? 'CarZone منصة تربط معارض السيارات بالمشترين.';
$bodyClass = $bodyClass ?? '';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta name="theme-color" content="#060d19">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:image" content="<?= $base ?>images/logo-full.jpg">
<link rel="icon" href="<?= $base ?>images/logo-header.jpg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&family=Orbitron:wght@700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= $base ?>style.css">
</head>
<body class="<?= e($bodyClass) ?>">

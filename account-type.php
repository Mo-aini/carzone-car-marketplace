<?php
require __DIR__ . '/config.php';
$title = 'اختر نوع الحساب | CarZone';
$desc = 'اختر: صاحب معرض أو عميل.';
$bodyClass = 'app-page cat-page';
require __DIR__ . '/includes/head.php';
?>
<div class="cat-bg" aria-hidden="true"><img class="cat-car" src="images/catalog-car.jpg" alt=""><div class="cat-hex"></div><div class="cat-glow"></div></div>
<header class="app-top"><a class="pill-link" href="index.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/></svg> الرئيسية</a><a class="pill-link back-link" href="index.php" aria-label="رجوع للرئيسية">رجوع للرئيسية <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg></a></header>
<main class="app-main">
  <!-- SCREEN 2: choose account type -->
  <h1 class="app-title">اختر نوع الحساب</h1>
  <p class="app-sub">كيف تبغى تستخدم CarZone؟</p>
  <a class="role-option" style="--d:1" href="register.php?role=dealer" data-role="dealer">
    <img src="images/role-dealer.jpg" alt="صاحب معرض">
    <span><strong>تسجيل دخول لعرض السيارات</strong><small>لأصحاب المعارض</small></span>
  </a>
  <a class="role-option" style="--d:2" href="register.php?role=customer" data-role="customer">
    <img src="images/role-customer.jpg" alt="عميل">
    <span><strong>تسجيل دخول لتصفح السيارات</strong><small>للعملاء</small></span>
  </a>
</main>
<?php require __DIR__ . '/includes/app-end.php'; ?>

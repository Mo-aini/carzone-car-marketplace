<?php
/* header.php — الهيدر المشترك لكل صفحات الموقع.
   الأزرار تتغير حسب حالة الدخول (زائر / عميل / صاحب معرض / مدير) */
require __DIR__ . '/head.php';
$u = current_user();
$nav = [
    'index.php' => 'الرئيسية', 'catalog.php' => 'الكتالوج', 'cars.php' => 'السيارات',
    'services.php' => 'الخدمات', 'about.php' => 'من نحن', 'contact.php' => 'تواصل معنا',
];
$navHtml = '';
foreach ($nav as $href => $label) $navHtml .= '<a href="' . $base . $href . '">' . $label . '</a>';
?>
<!-- ===== HEADER (same on every page) ===== -->
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="<?= $base ?>index.php" aria-label="CarZone الصفحة الرئيسية">
      <img src="<?= $base ?>images/logo-header.jpg" alt="شعار CarZone" width="70" height="44">
      <span class="brand-name latin">Car<span>Zone</span></span>
    </a>
    <nav class="main-nav" aria-label="القائمة الرئيسية"><?= $navHtml ?></nav>
    <div class="header-actions">
      <?php if ($u): ?>
        <a class="btn btn-ghost btn-sm btn-login" href="<?= $base ?>logout.php">تسجيل الخروج</a>
        <a class="btn btn-primary btn-sm btn-list" href="<?= e(dashboard_url($base)) ?>"><?= $u['role'] === 'customer' ? 'السيارات' : 'لوحة التحكم' ?></a>
      <?php else: ?>
        <a class="btn btn-ghost btn-sm btn-login" href="<?= $base ?>login.php">تسجيل الدخول</a>
        <a class="btn btn-primary btn-sm btn-list" href="<?= $base ?>dealer-subscribe.php">سجّل معرضك</a>
      <?php endif; ?>
      <button class="menu-toggle" type="button" aria-label="القائمة" aria-expanded="false"><span></span></button>
    </div>
  </div>
  <nav class="mobile-nav" aria-label="قائمة الجوال">
    <?= $navHtml ?>
    <div class="mobile-buttons">
      <?php if ($u): ?>
        <a class="btn btn-primary" href="<?= e(dashboard_url($base)) ?>"><?= $u['role'] === 'customer' ? 'السيارات' : 'لوحة التحكم' ?></a>
        <a class="btn btn-ghost" href="<?= $base ?>logout.php">تسجيل الخروج (<?= e($u['username']) ?>)</a>
      <?php else: ?>
        <a class="btn btn-primary" href="<?= $base ?>dealer-subscribe.php">سجّل معرضك</a>
        <a class="btn btn-ghost" href="<?= $base ?>login.php">تسجيل الدخول</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
<?php $flashHtml = show_flash(); if ($flashHtml): ?><div class="container flash-wrap"><?= $flashHtml ?></div><?php endif; ?>

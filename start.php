<?php
require __DIR__ . '/config.php';
$title = 'CarZone — Drive Your Next';
$desc = 'CarZone منصة تربط معارض السيارات بالمشترين.';
$bodyClass = 'app-page cat-page';
require __DIR__ . '/includes/head.php';
?>
<div class="cat-bg" aria-hidden="true"><img class="cat-car" src="images/catalog-car.jpg" alt=""><div class="cat-hex"></div><div class="cat-glow"></div></div>
<header class="app-top"><a class="pill-link" href="index.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/></svg> الرئيسية</a></header>
<main class="app-main">
  <!-- SCREEN 1: splash / start -->
  <section class="splash">
    <div class="splash-logo"><img src="images/logo-round.jpg" alt="شعار CarZone"></div>
    <!-- EDIT: platform description -->
    <p><strong>CarZone</strong> منصة تربط معارض السيارات بالمشترين. أصحاب المعارض يعرضون سياراتهم، والعملاء يتصفحونها من مكان واحد ويتواصلون مع المعرض مباشرة.</p>
    <a class="btn btn-primary" href="catalog.php">دخول</a>
  </section>
</main>
<?php require __DIR__ . '/includes/app-end.php'; ?>

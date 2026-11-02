<?php
require __DIR__ . '/config.php';
$title = 'كتالوج السيارات | CarZone — Drive Your Next';
$desc = 'كتالوج CarZone: تصفح السيارات الجديدة والمستعملة، أو سجّل اشتراكك كصاحب معرض أو كعميل.';
$bodyClass = 'cat-page';
require __DIR__ . '/includes/head.php';
?>
<!-- =====================================================
     CATALOG MENU — the main menu after the splash screen
     Buttons go to: cars.php (new / used), register.php (dealer / customer)
     ===================================================== -->
<div class="cat-bg" aria-hidden="true">
  <img class="cat-car" src="images/catalog-car.jpg" alt="">
  <div class="cat-hex"></div>
  <div class="cat-glow"></div>
</div>

<header class="cat-top">
  <a class="pill-link" href="index.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M10 21v-6h4v6"/></svg> الرئيسية</a>
  <span class="latin">DRIVE YOUR NEXT</span>
</header>

<main class="cat-main">
  <div class="cat-panel">
    <div class="cat-logo"><img src="images/logo-round.jpg" alt="شعار CarZone"></div>
    <h1>كتالوج السيارات</h1>
    <p class="cat-sub">اختر ما يناسبك من عالم السيارات</p>

    <div class="cat-group-title" style="--d:0">تصفّح السيارات</div>
    <nav class="cat-buttons" aria-label="تصفح السيارات">
      <a class="glass-btn" style="--d:1" href="cars.php?condition=new">
        <span class="g-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 5.6L19.5 10l-5.6 1.9L12 17.5l-1.9-5.6L4.5 10l5.6-1.4z"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z"/></svg></span>
        <span><strong>سيارات جديدة</strong><small>أحدث الموديلات من المعارض</small></span>
        <span class="g-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></span>
      </a>
      <a class="glass-btn" style="--d:2" href="cars.php?condition=used">
        <span class="g-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 17h-.5A1.5 1.5 0 0 1 3 15.5V12l2-5.5A2 2 0 0 1 6.9 5h10.2a2 2 0 0 1 1.9 1.5L21 12v3.5a1.5 1.5 0 0 1-1.5 1.5H19"/><path d="M3 12h18"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M9 17h6"/></svg></span>
        <span><strong>سيارات مستعملة</strong><small>تصفّح السيارات المستعملة</small></span>
        <span class="g-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></span>
      </a>
    </nav>

    <div class="cat-group-title" style="--d:3">الاشتراك</div>
    <nav class="cat-buttons" aria-label="الاشتراك">
      <a class="glass-btn alt" style="--d:4" href="register.php?role=dealer" data-role="dealer">
        <span class="g-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l1.5-5h15L21 9"/><path d="M3 9h18v2a3 3 0 0 1-6 0 3 3 0 0 1-6 0 3 3 0 0 1-6 0z"/><path d="M5 13v8h14v-8"/><path d="M10 21v-5h4v5"/></svg></span>
        <span><strong>تسجيل الاشتراك الخاص بأصحاب المعارض</strong><small>اعرض سيارات معرضك للمشترين</small></span>
        <span class="g-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></span>
      </a>
      <a class="glass-btn alt" style="--d:5" href="register.php?role=customer" data-role="customer">
        <span class="g-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg></span>
        <span><strong>تسجيل الاشتراك الخاص بالعملاء</strong><small>أنشئ حسابك وتصفّح السيارات</small></span>
        <span class="g-arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></span>
      </a>
    </nav>

    <p class="cat-note">لديك حساب؟ <a href="login.php">تسجيل الدخول</a></p>
  </div>
</main>

<!-- HELP button (bottom-left) and its panel -->
<button class="help-btn" type="button" aria-expanded="false" aria-controls="help-panel"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg><span>مساعدة</span></button>
<div class="help-panel" id="help-panel" role="dialog" aria-label="مساعدة">
  <button class="help-close" type="button" aria-label="إغلاق"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
  <h2>كيف نقدر نساعدك؟</h2>
  <p>تواصل معنا على واتساب وفريقنا يرد عليك في أوقات العمل.</p>
  <!-- EDIT: WhatsApp number (replace 9677XXXXXXXX with your number, no + or spaces) -->
  <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> راسلنا على واتساب</a>
  <!-- EDIT: phone -->
  <a class="btn btn-ghost" href="tel:+9677XXXXXXXX"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg> <span dir="ltr">+967 7XX XXX XXX</span></a>
</div>
<?php require __DIR__ . '/includes/app-end.php'; ?>

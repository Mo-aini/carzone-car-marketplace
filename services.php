<?php
require __DIR__ . '/config.php';
$title = 'الخدمات | CarZone';
$desc = 'خدمات CarZone: شراء، بيع، استبدال، تمويل، وفحص السيارات.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">الرئيسية</a> / الخدمات</p>
    <h1>خدماتنا</h1>
    <p class="lead">شراء، بيع، استبدال، مساعدة في التمويل، وفحص السيارات.</p>
  </div>
</section>
<section class="section"><div class="container service-list">
    <article class="service-row" id="buy">
      <img src="images/service-handover.jpg" alt="شراء سيارة" loading="lazy">
      <div class="body">
        <div class="icon" style="width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:rgba(31,123,255,.15);color:var(--blue-2);margin-bottom:12px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg></div>
        <h2 style="font-size:1.5rem">شراء سيارة</h2>
        <p class="lead">تصفّح سيارات المعارض المشتركة، قارن الأسعار وتواصل مع المعرض مباشرة.</p>
        <ul><li>تصفّح سيارات معارض متعددة</li><li>فلترة حسب النوع والحالة</li><li>تواصل مباشر مع المعرض</li></ul>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> اسأل على واتساب</a>
      </div>
    </article>
    <article class="service-row" id="sell">
      <img src="images/about-dealer.jpg" alt="بيع سيارتك" loading="lazy">
      <div class="body">
        <div class="icon" style="width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:rgba(31,123,255,.15);color:var(--blue-2);margin-bottom:12px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg></div>
        <h2 style="font-size:1.5rem">بيع سيارتك</h2>
        <p class="lead">اعرض سيارتك علينا ونساعدك توصل لمشترين جادين بسرعة.</p>
        <ul><li>نعرض سيارتك للمشترين</li><li>صور ومواصفات واضحة</li><li>تواصل مباشر مع المهتمين</li></ul>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> اسأل على واتساب</a>
      </div>
    </article>
    <article class="service-row" id="trade">
      <img src="images/service-key.jpg" alt="استبدال (Trade-in)" loading="lazy">
      <div class="body">
        <div class="icon" style="width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:rgba(31,123,255,.15);color:var(--blue-2);margin-bottom:12px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 3l4 4-4 4"/><path d="M21 7H8"/><path d="M7 21l-4-4 4-4"/><path d="M3 17h13"/></svg></div>
        <h2 style="font-size:1.5rem">استبدال (Trade-in)</h2>
        <p class="lead">بدّل سيارتك الحالية بسيارة أحدث وادفع الفرق.</p>
        <ul><li>تقييم سيارتك الحالية</li><li>اختيار سيارة أحدث</li><li>تدفع الفرق فقط</li></ul>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> اسأل على واتساب</a>
      </div>
    </article>
    <article class="service-row" id="finance">
      <img src="images/service-interior.jpg" alt="المساعدة في التمويل" loading="lazy">
      <div class="body">
        <div class="icon" style="width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:rgba(31,123,255,.15);color:var(--blue-2);margin-bottom:12px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg></div>
        <h2 style="font-size:1.5rem">المساعدة في التمويل</h2>
        <p class="lead">نرشدك لخيارات التقسيط والتمويل المتاحة ونساعدك في تجهيز الطلب.</p>
        <ul><li>نرشدك لخيارات التقسيط</li><li>نساعدك تجهز الأوراق المطلوبة</li><li>القرار النهائي لجهة التمويل</li></ul>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> اسأل على واتساب</a>
      </div>
    </article>
    <article class="service-row" id="inspect">
      <img src="images/service-inspection.jpg" alt="فحص السيارة" loading="lazy">
      <div class="body">
        <div class="icon" style="width:50px;height:50px;border-radius:14px;display:grid;place-items:center;background:rgba(31,123,255,.15);color:var(--blue-2);margin-bottom:12px"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.7 6.3a4 4 0 0 0 5 5L21 13l-8 8-8-8 1.7-1.3a4 4 0 0 0 5-5L13 5z" transform="rotate(0)"/></svg></div>
        <h2 style="font-size:1.5rem">فحص السيارة</h2>
        <p class="lead">فحص قبل الشراء عشان تعرف حالة السيارة وتشتري وأنت مرتاح.</p>
        <ul><li>فحص قبل الشراء</li><li>تعرف حالة السيارة قبل الدفع</li><li>تشتري وأنت مرتاح</li></ul>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> اسأل على واتساب</a>
      </div>
    </article>
</div></section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

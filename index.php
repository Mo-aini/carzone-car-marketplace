<?php
require __DIR__ . '/config.php';
// أحدث 6 سيارات من المعارض المقبولة
$featured = db()->query(CAR_SELECT . " WHERE d.status = 'approved' ORDER BY c.is_sample ASC, c.created_at DESC, c.id ASC LIMIT 6")->fetchAll();
// قائمة المدن لنموذج البحث
$cities = db()->query("SELECT DISTINCT c.city FROM cars c JOIN dealers d ON d.id=c.dealer_id WHERE d.status='approved' AND c.city <> '' ORDER BY c.city")->fetchAll(PDO::FETCH_COLUMN);
$title = 'CarZone — Drive Your Next | منصة معارض السيارات';
$desc = 'CarZone: سيارات من معارض متعددة في مكان واحد. اشترِ، بِع، استبدل، وتواصل مع المعرض مباشرة.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-bg" role="img" aria-label="معرض سيارات"></div>
  <div class="container hero-grid">
    <div class="hero-visual"><img class="hero-logo" src="images/logo-round.jpg" alt="شعار CarZone — DRIVE YOUR NEXT"></div>
    <div class="hero-text">
      <!-- EDIT: city -->
      <span class="eyebrow">منصة معارض السيارات · صنعاء</span>
      <h1>قُد سيارتك <span class="blue-text">القادمة</span></h1>
      <p class="hero-latin latin chrome-text">DRIVE YOUR NEXT</p>
      <p class="lead">سيارات من معارض متعددة في مكان واحد. اشترِ، بِع، أو استبدل سيارتك، وتواصل مع المعرض مباشرة على واتساب.</p>
      <div class="hero-ctas">
        <a class="btn btn-primary" href="cars.php"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg> تصفّح السيارات</a>
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> راسلنا واتساب</a>
      </div>
      <form class="search-box" action="cars.php" method="get">
        <div class="field"><label for="s-cond">الحالة</label>
          <select id="s-cond" name="condition"><option value="all">الكل</option><option value="new">جديدة</option><option value="used">مستعملة</option></select></div>
        <div class="field"><label for="s-type">النوع</label>
          <select id="s-type" name="type"><option value="all">الكل</option><option value="sedan">سيدان</option><option value="suv">SUV</option><option value="hatchback">هاتشباك</option><option value="luxury">فاخرة</option></select></div>
        <div class="field"><label for="s-city">المدينة</label>
          <!-- المدن تُقرأ من قاعدة البيانات -->
          <select id="s-city" name="city"><option value="all">الكل</option><?php foreach ($cities as $ct): ?><option value="<?= e($ct) ?>"><?= e($ct) ?></option><?php endforeach; ?></select></div>
        <button class="btn btn-primary" type="submit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg> ابحث</button>
      </form>
    </div>
  </div>
</section>

<!-- ===== CATEGORIES ===== -->
<section class="section" style="padding-top:20px">
  <div class="container"><div class="chips"><a class="chip-card" href="cars.php?type=sedan"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 17h-.5A1.5 1.5 0 0 1 3 15.5V12l2-5.5A2 2 0 0 1 6.9 5h10.2a2 2 0 0 1 1.9 1.5L21 12v3.5a1.5 1.5 0 0 1-1.5 1.5H19"/><path d="M3 12h18"/><circle cx="7" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M9 17h6"/></svg></span><span>سيدان<small>عملية واقتصادية</small></span></a><a class="chip-card" href="cars.php?type=suv"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 16v-4l2-5h12l4 5h2v4h-2"/><path d="M2 12h18"/><circle cx="6.5" cy="16.5" r="2"/><circle cx="16.5" cy="16.5" r="2"/><path d="M8.5 16.5h6"/><path d="M10 7v5"/></svg></span><span>SUV<small>للعائلة والسفر</small></span></a><a class="chip-card" href="cars.php?type=hatchback"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 16v-3l3-6h8l6 6v3h-1"/><path d="M3 13h17"/><circle cx="7" cy="16.5" r="2"/><circle cx="16" cy="16.5" r="2"/><path d="M9 16.5h5"/></svg></span><span>هاتشباك<small>صغيرة ومرنة</small></span></a><a class="chip-card" href="cars.php?type=luxury"><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7l4 4 5-6 5 6 4-4-2 11H5z"/><path d="M5 21h14"/></svg></span><span>فاخرة<small>فخامة وأداء</small></span></a></div></div>
</section>

<!-- ===== FEATURED CARS ===== -->
<section class="section alt">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">سيارات مميزة</span>
      <h2>اختر سيارتك <span class="blue-text">القادمة</span></h2>
      <p class="lead">أحدث السيارات المضافة من المعارض (من قاعدة البيانات).</p>
    </div>
    <div class="car-grid">
      <?php foreach ($featured as $car) echo car_card($car); ?>
      <?php if (!$featured): ?><p class="lead">لا توجد سيارات معروضة حالياً.</p><?php endif; ?>
    </div>
    <p style="text-align:center;margin-top:28px"><a class="btn btn-ghost" href="cars.php">عرض كل السيارات</a></p>
  </div>
</section>

<!-- ===== WHY CHOOSE US ===== -->
<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">ليش CarZone؟</span>
      <h2>كل اللي تحتاجه في مكان واحد</h2>
    </div>
    <div class="grid-4">
      <div class="feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l1.5-5h15L21 9"/><path d="M3 9h18v2a3 3 0 0 1-6 0 3 3 0 0 1-6 0 3 3 0 0 1-6 0z"/><path d="M5 13v8h14v-8"/><path d="M10 21v-5h4v5"/></svg></div><h3>معارض كثيرة في مكان واحد</h3><p>بدل ما تلف على المعارض، شوف سياراتهم كلها من جوالك.</p></div>
      <div class="feature"><div class="icon"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg></div><h3>تواصل مباشر</h3><p>كلّم المعرض أو راسله على واتساب بضغطة زر.</p></div>
      <div class="feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div><h3>فحص قبل الشراء</h3><p>نساعدك تفحص السيارة قبل ما تدفع.</p></div>
      <div class="feature"><div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/></svg></div><h3>مساعدة في التمويل</h3><p>نرشدك لخيارات التقسيط المناسبة لك.</p></div>
    </div>
  </div>
</section>

<!-- ===== SERVICES ===== -->
<section class="section alt">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">خدماتنا</span>
      <h2>شراء، بيع، استبدال، تمويل وفحص</h2>
    </div>
    <div class="grid-3">
      <a class="service-card" href="services.php#buy"><img src="images/service-handover.jpg" alt="شراء سيارة" loading="lazy"><div class="body"><span class="num latin">01</span><h3>شراء سيارة</h3><p>تصفّح سيارات المعارض المشتركة، قارن الأسعار وتواصل مع المعرض مباشرة.</p></div></a>
      <a class="service-card" href="services.php#sell"><img src="images/about-dealer.jpg" alt="بيع سيارتك" loading="lazy"><div class="body"><span class="num latin">02</span><h3>بيع سيارتك</h3><p>اعرض سيارتك علينا ونساعدك توصل لمشترين جادين بسرعة.</p></div></a>
      <a class="service-card" href="services.php#trade"><img src="images/service-key.jpg" alt="استبدال (Trade-in)" loading="lazy"><div class="body"><span class="num latin">03</span><h3>استبدال (Trade-in)</h3><p>بدّل سيارتك الحالية بسيارة أحدث وادفع الفرق.</p></div></a>
      <a class="service-card" href="services.php#finance"><img src="images/service-interior.jpg" alt="المساعدة في التمويل" loading="lazy"><div class="body"><span class="num latin">04</span><h3>المساعدة في التمويل</h3><p>نرشدك لخيارات التقسيط والتمويل المتاحة ونساعدك في تجهيز الطلب.</p></div></a>
      <a class="service-card" href="services.php#inspect"><img src="images/service-inspection.jpg" alt="فحص السيارة" loading="lazy"><div class="body"><span class="num latin">05</span><h3>فحص السيارة</h3><p>فحص قبل الشراء عشان تعرف حالة السيارة وتشتري وأنت مرتاح.</p></div></a>
    </div>
  </div>
</section>

<!-- ===== FOR SHOWROOM OWNERS ===== -->
<section class="section">
  <div class="container">
    <div class="dealer-band">
      <div>
        <span class="eyebrow">لأصحاب المعارض</span>
        <h2>اعرض سيارات معرضك على CarZone</h2>
        <p class="lead">اشترك وابدأ تعرض سياراتك للمشترين في صنعاء.</p>
        <ul><li>صفحة خاصة لمعرضك</li><li>إضافة سياراتك بالصور والمواصفات</li><li>العملاء يتواصلون معك مباشرة</li></ul>
        <a class="btn btn-primary" href="dealer-subscribe.php">سجّل معرضك الآن</a>
      </div>
      <img src="images/about-dealer.jpg" alt="صاحب معرض مع عميل" loading="lazy">
    </div>
  </div>
</section>

<!-- ===== CTA BAND ===== -->
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="band">
      <h2>جاهز تقود سيارتك القادمة؟</h2>
      <p>راسلنا على واتساب أو اتصل، ونساعدك تلقى السيارة المناسبة.</p>
      <div class="actions">
        <!-- EDIT: WhatsApp number -->
        <a class="btn btn-whatsapp" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> واتساب</a>
        <!-- EDIT: phone -->
        <a class="btn btn-ghost" href="tel:+9677XXXXXXXX"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg> <span dir="ltr">+967 7XX XXX XXX</span></a>
      </div>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

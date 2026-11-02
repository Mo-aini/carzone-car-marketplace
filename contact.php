<?php
require __DIR__ . '/config.php';
// نموذج التواصل: يحفظ الرسالة في جدول messages
$sent = isset($_GET['sent']);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name === '' || $phone === '' || $message === '') {
        $error = 'اكتب الاسم والرقم والرسالة';
    } else {
        $st = db()->prepare('INSERT INTO messages (name, phone, subject, message) VALUES (?, ?, ?, ?)');
        $st->execute([mb_substr($name, 0, 100), mb_substr($phone, 0, 30), mb_substr($subject, 0, 100), $message]);
        redirect('contact.php?sent=1#contact');
    }
}
$title = 'تواصل معنا | CarZone';
$desc = 'تواصل مع CarZone: هاتف، واتساب، العنوان وأوقات العمل.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">الرئيسية</a> / تواصل معنا</p>
    <h1>تواصل معنا</h1>
    <p class="lead">راسلنا أو اتصل، ونرد عليك في أقرب وقت.</p>
  </div>
</section>

<section class="section">
  <div class="container contact-grid">
    <div class="form-card">
      <h2 style="font-size:1.4rem">أرسل رسالة</h2>
      <form id="contact" data-validate data-server method="post" action="contact.php#contact">
        <?= csrf_field() ?>
        <div class="form-grid two">
          <div class="field"><label for="c-name">الاسم</label><input id="c-name" name="name" required autocomplete="name"><span class="err">اكتب اسمك</span></div>
          <div class="field"><label for="c-phone">رقم الجوال / واتساب</label><input id="c-phone" name="phone" type="tel" required dir="ltr" placeholder="+967 7XX XXX XXX" autocomplete="tel"><span class="err">اكتب رقمك</span></div>
          <div class="field full"><label for="c-sub">الموضوع</label><select id="c-sub" name="subject"><option>شراء سيارة</option><option>بيع سيارة</option><option>استبدال</option><option>تمويل</option><option>فحص</option><option>اشتراك معرض</option><option>أخرى</option></select></div>
          <div class="field full"><label for="c-msg">الرسالة</label><textarea id="c-msg" name="message" required></textarea><span class="err">اكتب رسالتك</span></div>
          <div class="full"><button class="btn btn-primary btn-block" type="submit">إرسال</button></div>
        </div>
        <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <div class="form-success<?= $sent ? ' show' : '' ?>" role="status">تم استلام رسالتك ✓ وحُفظت عندنا، وبنرد عليك قريب.</div>
      </form>
    </div>
    <div class="stack">
      <div class="box">
        <ul class="info-list">
          <!-- EDIT: phone -->
          <li><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg></span><div><small>الهاتف</small><a href="tel:+9677XXXXXXXX" dir="ltr">+967 7XX XXX XXX</a></div></li>
          <!-- EDIT: WhatsApp number -->
          <li><span class="icon"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg></span><div><small>واتساب</small><a href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener" class="link">راسلنا الآن</a></div></li>
          <!-- EDIT: address / city -->
          <li><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><div><small>العنوان</small>عنوان المعرض، صنعاء</div></li>
          <!-- EDIT: email -->
          <li><span class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg></span><div><small>الإيميل</small><span dir="ltr">email@example.com</span></div></li>
        </ul>
        <a class="btn btn-whatsapp btn-block" style="margin-top:18px" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> واتساب</a>
      </div>
      <div class="box">
        <h2><svg style="width:20px;height:20px;vertical-align:-3px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg> أوقات العمل</h2>
        <!-- EDIT: opening hours -->
        <table class="hours"><tr><td>السبت</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الأحد</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الاثنين</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الثلاثاء</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الأربعاء</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الخميس</td><td dir="ltr">--:-- – --:--</td></tr><tr><td>الجمعة</td><td dir="ltr">--:-- – --:--</td></tr></table>
      </div>
    </div>
  </div>
  <div class="container" style="margin-top:24px">
    <!-- EDIT: map — in Google Maps: Share > Embed a map > copy the <iframe> and paste it here instead of this box -->
    <div class="map-placeholder"><div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><strong>الخريطة</strong>ضع هنا خريطة Google لموقع المعرض</div></div>
  </div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

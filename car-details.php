<?php
require __DIR__ . '/config.php';
// جلب السيارة من قاعدة البيانات حسب رقمها ?id=
$id = (int)($_GET['id'] ?? 0);
$st = db()->prepare("SELECT c.*, d.showroom_name, d.address AS dealer_address, d.phone AS dealer_phone, d.status AS dealer_status
                     FROM cars c JOIN dealers d ON d.id = c.dealer_id WHERE c.id = ?");
$st->execute([$id]);
$car = $st->fetch();
// السيارة غير موجودة، أو معرضها غير مقبول (إلا للأدمن أو صاحب السيارة)
$owner = is_role('dealer') && ($d = current_dealer()) && $car && (int)$d['id'] === (int)$car['dealer_id'];
if (!$car || ($car['dealer_status'] !== 'approved' && !is_role('admin') && !$owner)) {
    http_response_code(404);
    flash('السيارة غير موجودة', 'error');
    redirect('cars.php');
}
// الصور
$st = db()->prepare('SELECT path FROM car_images WHERE car_id = ? ORDER BY id');
$st->execute([$id]);
$images = $st->fetchAll(PDO::FETCH_COLUMN) ?: ['images/catalog-car.jpg'];
// رقم المعرض للاتصال والواتساب
$digits = preg_replace('/\D+/', '', (string)$car['dealer_phone']);
$tel = $digits ? '+' . $digits : '+9677XXXXXXXX';
$wa  = $digits ?: '9677XXXXXXXX';
$initials = mb_strtoupper(mb_substr(preg_replace('/[^A-Za-z]/', '', $car['showroom_name']) ?: 'CZ', 0, 2));
// سيارات مشابهة (نفس النوع)
$st = db()->prepare(CAR_SELECT . " WHERE d.status = 'approved' AND c.type = ? AND c.id <> ? ORDER BY RAND() LIMIT 3");
$st->execute([$car['type'], $id]);
$similar = $st->fetchAll();
$title = $car['title'] . ' | CarZone';
$desc = 'تفاصيل السيارة: الصور، السعر، المواصفات، والتواصل مع المعرض.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="section" style="padding-top:28px">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">الرئيسية</a> / <a href="cars.php">السيارات</a> / <?= e($car['title']) ?></p>
    <div class="detail-grid">
      <div class="d-gallery">
        <!-- صور السيارة من جدول car_images -->
        <div>
          <div class="gallery-main"><img src="<?= e($images[0]) ?>" alt="<?= e($car['title']) ?>"></div>
          <?php if (count($images) > 1): ?>
          <div class="thumbs" style="grid-template-columns:repeat(<?= min(count($images), 4) ?>,1fr)"><?php foreach ($images as $i => $img): ?><button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-src="<?= e($img) ?>" aria-label="الصورة <?= $i + 1 ?>"><img src="<?= e($img) ?>" alt=""></button><?php endforeach; ?></div>
          <?php endif; ?>
        </div>
      </div>
      <aside class="detail-side stack">
        <div class="box">
          <?php if ($car['is_sample']): ?><span class="badge" style="position:static;display:inline-block">إعلان تجريبي</span><?php endif; ?>
          <div class="detail-title">
            <h1><?= e($car['title']) ?></h1>
            <p class="loc"><?= e(type_label($car['type'])) ?> · <?= e(cond_label($car['condition'])) ?> · <?= e($car['city'] ?: 'صنعاء') ?></p>
          </div>
          <div class="detail-price" dir="ltr"><?= e(price_fmt($car['price'])) ?></div>
          <div class="contact-actions">
            <a class="btn btn-primary btn-block" href="tel:<?= e($tel) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"/></svg> اتصال</a>
            <a class="btn btn-whatsapp btn-block" href="https://wa.me/<?= e($wa) ?>" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg> واتساب</a>
          </div>
        </div>
        <div class="box">
          <h2>المعرض</h2>
          <div class="dealer-card"><div class="dealer-avatar latin"><?= e($initials) ?></div><div><strong><?= e($car['showroom_name']) ?></strong><span>معرض مشترك في CarZone · <?= e($car['dealer_address'] ?: 'صنعاء') ?></span></div></div>
          <a class="btn btn-ghost btn-block" href="cars.php?dealer=<?= (int)$car['dealer_id'] ?>">كل سيارات هذا المعرض</a>
        </div>
      </aside>
      <div class="d-info stack">
        <div class="box">
          <h2>الوصف</h2>
          <p><?= nl2br(e($car['description'] ?: 'لا يوجد وصف.')) ?></p>
        </div>
        <div>
          <h2 style="font-size:1.2rem">المواصفات</h2>
          <table class="spec-table"><tr><th>الماركة</th><td><?= e($car['brand'] ?: '—') ?></td></tr><tr><th>الموديل</th><td><?= e($car['model'] ?: '—') ?></td></tr><tr><th>السنة</th><td><?= e($car['year'] ?: '—') ?></td></tr><tr><th>الممشى</th><td><?= e(mileage_fmt($car['mileage'])) ?></td></tr><tr><th>الوقود</th><td><?= e($car['fuel'] ?: '—') ?></td></tr><tr><th>ناقل الحركة</th><td><?= e($car['transmission'] ?: '—') ?></td></tr><tr><th>اللون</th><td><?= e($car['color'] ?: '—') ?></td></tr><tr><th>المدينة</th><td><?= e($car['city'] ?: 'صنعاء') ?></td></tr></table>
        </div>
      </div>
    </div>
  </div>
</section>
<?php if ($similar): ?>
<section class="section alt">
  <div class="container">
    <div class="section-head"><h2>سيارات مشابهة</h2></div>
    <div class="car-grid">
      <?php foreach ($similar as $s) echo car_card($s); ?>
    </div>
  </div>
</section>
<?php endif; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

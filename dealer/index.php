<?php
/* =========================================================
   dealer/index.php — لوحة تحكم صاحب المعرض
   تعرض حالة المعرض وسياراته مع أزرار التعديل والحذف
   ========================================================= */
require __DIR__ . '/../config.php';
require_role('dealer', '../dealer-login.php');      // حماية: أصحاب المعارض فقط

$dealer = current_dealer();
if (!$dealer) redirect('../dealer-info.php');         // لم يكمل بيانات المعرض

// سيارات هذا المعرض فقط
$st = db()->prepare('SELECT c.*, (SELECT path FROM car_images WHERE car_id = c.id ORDER BY id LIMIT 1) AS image
                     FROM cars c WHERE c.dealer_id = ? ORDER BY c.created_at DESC, c.id DESC');
$st->execute([$dealer['id']]);
$cars = $st->fetchAll();

$base = '../';
$title = 'لوحة صاحب المعرض | CarZone';
$dashTitle = 'لوحة صاحب المعرض';
$dashSub = e($dealer['showroom_name']) . ' · الحالة: <span class="status status-' . e($dealer['status']) . '">' . e(status_label($dealer['status'])) . '</span>';
require __DIR__ . '/_tabs.php';
$activeTab = 'index.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">

    <?php if ($dealer['status'] === 'pending'): ?>
      <div class="notice" style="background:rgba(255,200,87,.12);border-color:rgba(255,200,87,.4);color:#ffe2a3">
        طلبك قيد المراجعة ⏳ الأدمن يراجع ترخيص المعرض. بعد القبول تقدر تضيف سياراتك.
        <?php if (!$dealer['license_image']): ?><br><a class="link" href="../dealer-license.php">أرفق صورة الترخيص الآن</a><?php endif; ?>
        <?php if (!$dealer['plan_id']): ?><br><a class="link" href="../dealer-subscribe.php">اختر باقة الاشتراك</a><?php endif; ?>
      </div>
    <?php elseif ($dealer['status'] === 'rejected'): ?>
      <div class="notice notice-error">تم رفض طلب المعرض. ارفع صورة ترخيص أوضح من <a class="link" href="../dealer-license.php">صفحة الترخيص</a> وسيُعاد الطلب للمراجعة.</div>
    <?php endif; ?>

    <div class="stats">
      <div class="stat"><span class="num"><?= count($cars) ?></span><span class="lbl">سياراتي</span></div>
      <div class="stat"><span class="num"><?= (int)$dealer['max_listings'] ?: '—' ?></span><span class="lbl">حد الباقة</span></div>
      <div class="stat"><span class="num latin" style="font-size:1.2rem"><?= e($dealer['plan_name'] ?: '—') ?></span><span class="lbl">الباقة</span></div>
      <div class="stat <?= $dealer['status'] === 'approved' ? '' : 'warn' ?>"><span class="num" style="font-size:1.2rem"><?= e(status_label($dealer['status'])) ?></span><span class="lbl">حالة المعرض</span></div>
    </div>

    <div class="dash-head">
      <h2>سياراتي</h2>
      <?php if ($dealer['status'] === 'approved'): ?>
        <a class="btn btn-primary btn-sm" href="car-form.php">+ إضافة سيارة</a>
      <?php endif; ?>
    </div>

    <?php if (!$cars): ?>
      <p class="empty box">لا توجد سيارات بعد.</p>
    <?php else: ?>
      <div class="my-cars">
        <?php foreach ($cars as $c): ?>
          <div class="my-car">
            <img src="<?= e($base . ($c['image'] ?: 'images/catalog-car.jpg')) ?>" alt="<?= e($c['title']) ?>">
            <div>
              <h3><?= e($c['title']) ?></h3>
              <div class="meta"><?= e(cond_label($c['condition'])) ?> · <?= e(type_label($c['type'])) ?> · <?= e($c['city']) ?></div>
              <div class="meta" dir="ltr" style="text-align:right;color:var(--blue-2);font-weight:700"><?= e(price_fmt($c['price'])) ?></div>
            </div>
            <div class="actions">
              <a class="btn btn-ghost btn-xs" href="../car-details.php?id=<?= (int)$c['id'] ?>">عرض</a>
              <a class="btn btn-ghost btn-xs" href="car-form.php?id=<?= (int)$c['id'] ?>">تعديل</a>
              <form method="post" action="car-delete.php" onsubmit="return confirm('حذف هذه السيارة؟');">
                <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                <button class="btn btn-danger btn-xs" type="submit">حذف</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

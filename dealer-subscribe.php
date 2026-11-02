<?php
require __DIR__ . '/config.php';
// الخطوة 4: اختيار باقة الاشتراك — تُحفظ في dealers.plan_id
$plans = db()->query('SELECT * FROM plans ORDER BY price, id')->fetchAll();
$dealer = is_role('dealer') ? current_dealer() : null;
$current = $dealer['plan_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!$dealer) { flash('سجّل كصاحب معرض أولاً', 'error'); redirect('register.php?role=dealer'); }
    $planId = (int)($_POST['plan_id'] ?? 0);
    $st = db()->prepare('SELECT id FROM plans WHERE id = ?');
    $st->execute([$planId]);
    if ($st->fetch()) {
        db()->prepare('UPDATE dealers SET plan_id = ? WHERE id = ?')->execute([$planId, $dealer['id']]);
        flash($dealer['status'] === 'approved' ? 'تم تحديث الباقة ✓' : 'تم اختيار الباقة ✓ طلبك الآن قيد المراجعة من الأدمن');
        redirect('dealer/index.php');
    }
    flash('اختر باقة', 'error');
    redirect('dealer-subscribe.php');
}
$title = 'سجّل معرضك | CarZone';
$desc = 'اشتراك أصحاب معارض السيارات في CarZone: اختر الباقة وسجّل بيانات المعرض.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">الرئيسية</a> / اشتراك أصحاب المعارض</p>
    <h1>سجّل معرضك</h1>
    <p class="lead">اشترك واعرض سيارات معرضك للمشترين على CarZone.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <form id="dealer-subscribe" class="stack" method="post" action="dealer-subscribe.php">
      <?= csrf_field() ?>
      <div>
        <h2 style="font-size:1.3rem">1. اختر الباقة</h2>
        <div class="plans" style="margin-top:16px">
        <!-- الباقات تُقرأ من جدول plans (الأدمن يعدّل الأسعار من لوحة التحكم) -->
        <?php foreach ($plans as $i => $p): $checked = $current ? ((int)$current === (int)$p['id']) : ($i === 1); ?>
        <label class="plan"><?php if ($i === 1): ?><span class="tag">الأكثر طلباً</span><?php endif; ?><input type="radio" name="plan_id" value="<?= (int)$p['id'] ?>"<?= $checked ? ' checked' : '' ?>><span class="inner"><span class="name latin"><?= e(strtoupper($p['name'])) ?></span> <span style="color:var(--muted);font-size:.85rem">· <?= e($p['name_ar']) ?></span><span class="amount" dir="ltr" style="display:block"><?= e(price_fmt($p['price'])) ?></span><span class="per">شهرياً · حتى <?= (int)$p['max_listings'] ?: '—' ?> سيارة معروضة</span><ul><?php foreach (array_filter(array_map('trim', explode("\n", (string)$p['features']))) as $f): ?><li><?= e($f) ?></li><?php endforeach; ?></ul></span></label>
        <?php endforeach; ?>
        </div>
      </div>
      <div class="form-card">
        <?php if ($dealer): ?>
        <h2 style="font-size:1.3rem;margin-bottom:16px">2. تأكيد الاشتراك</h2>
        <p class="dev-note" style="text-align:start;margin:0 0 16px">المعرض: <strong><?= e($dealer['showroom_name']) ?></strong> · الحالة: <span class="status status-<?= e($dealer['status']) ?>"><?= e(status_label($dealer['status'])) ?></span></p>
        <button class="btn btn-primary btn-block" type="submit">اشترك الآن</button>
        <?php else: ?>
        <h2 style="font-size:1.3rem;margin-bottom:16px">2. بيانات المعرض</h2>
        <p class="lead" style="margin-bottom:16px">أنشئ حساب صاحب معرض أولاً: الحساب ← بيانات المعرض ← صورة الترخيص ← اختيار الباقة.</p>
        <a class="btn btn-primary btn-block" href="register.php?role=dealer">ابدأ تسجيل المعرض</a>
        <?php endif; ?>
        <p class="dev-note">عندك حساب؟ <a class="link" href="dealer-login.php">دخول أصحاب المعارض</a></p>
      </div>
    </form>
  </div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

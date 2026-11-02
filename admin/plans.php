<?php
/* admin/plans.php — تعديل باقات الاشتراك (السعر وعدد السيارات والمميزات) */
require __DIR__ . '/_guard.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $st = db()->prepare('UPDATE plans SET name_ar = ?, price = ?, max_listings = ?, features = ? WHERE id = ?');
    $st->execute([
        trim($_POST['name_ar'] ?? ''),
        max(0, (int)preg_replace('/\D+/', '', $_POST['price'] ?? '0')),
        max(0, (int)($_POST['max_listings'] ?? 0)),
        trim($_POST['features'] ?? ''),
        (int)($_POST['id'] ?? 0),
    ]);
    flash('تم حفظ الباقة ✓');
    redirect('plans.php');
}
$plans = db()->query('SELECT p.*, (SELECT COUNT(*) FROM dealers d WHERE d.plan_id = p.id) AS dealers_count FROM plans p ORDER BY p.id')->fetchAll();

$title = 'الباقات | لوحة الأدمن';
$dashTitle = 'باقات الاشتراك';
$dashSub = 'عدّل سعر كل باقة (بالريال اليمني) وعدد السيارات المسموح. السعر 0 يظهر "—" في الموقع.';
$activeTab = 'plans.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <div class="dash-grid" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
      <?php foreach ($plans as $p): ?>
        <form method="post" class="form-card">
          <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
          <h2 class="latin" style="font-size:1.1rem"><?= e(strtoupper($p['name'])) ?> <small style="color:var(--muted);font-family:var(--body)">· <?= (int)$p['dealers_count'] ?> معرض</small></h2>
          <div class="plan-edit">
            <div class="field"><label>الاسم بالعربي</label><input name="name_ar" value="<?= e($p['name_ar']) ?>"></div>
            <div class="field"><label>السعر الشهري</label><input name="price" dir="ltr" inputmode="numeric" value="<?= e((int)$p['price']) ?>"></div>
            <div class="field full"><label>أقصى عدد سيارات</label><input name="max_listings" type="number" min="0" dir="ltr" value="<?= (int)$p['max_listings'] ?>"></div>
            <div class="field full"><label>المميزات (كل ميزة في سطر)</label><textarea name="features" style="min-height:90px"><?= e($p['features']) ?></textarea></div>
            <div class="full"><button class="btn btn-primary btn-block" type="submit">حفظ</button></div>
          </div>
        </form>
      <?php endforeach; ?>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

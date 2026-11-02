<?php
/* =========================================================
   admin/index.php — الصفحة الرئيسية للوحة الأدمن (الإحصائيات)
   ========================================================= */
require __DIR__ . '/_guard.php';
$pdo = db();
// الإحصائيات: استعلامات COUNT من كل جدول
$stats = [
    'users'   => $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'dealers' => $pdo->query('SELECT COUNT(*) FROM dealers')->fetchColumn(),
    'cars'    => $pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM dealers WHERE status = 'pending'")->fetchColumn(),
];
$unread = $pdo->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
// آخر طلبات المعارض قيد المراجعة
$pending = $pdo->query("SELECT d.*, u.username FROM dealers d JOIN users u ON u.id = d.user_id
                        WHERE d.status = 'pending' ORDER BY d.created_at DESC LIMIT 5")->fetchAll();
// آخر السيارات المضافة
$latest = $pdo->query('SELECT c.id, c.title, c.created_at, d.showroom_name FROM cars c JOIN dealers d ON d.id = c.dealer_id
                       ORDER BY c.created_at DESC, c.id DESC LIMIT 5')->fetchAll();

$title = 'لوحة الأدمن | CarZone';
$dashTitle = 'لوحة الأدمن';
$dashSub = 'أهلاً ' . e(current_user()['username']) . ' — نظرة عامة على المنصة';
$activeTab = 'index.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <div class="stats">
      <div class="stat"><span class="num"><?= (int)$stats['users'] ?></span><span class="lbl">المستخدمون</span></div>
      <div class="stat"><span class="num"><?= (int)$stats['dealers'] ?></span><span class="lbl">المعارض</span></div>
      <div class="stat"><span class="num"><?= (int)$stats['cars'] ?></span><span class="lbl">السيارات</span></div>
      <div class="stat warn"><span class="num"><?= (int)$stats['pending'] ?></span><span class="lbl">طلبات قيد المراجعة</span></div>
    </div>

    <div class="dash-grid two">
      <div>
        <div class="dash-head"><h2>طلبات المعارض الجديدة</h2><a class="btn btn-ghost btn-sm" href="dealers.php">الكل</a></div>
        <div class="table-wrap">
          <table class="data-table stack-sm" style="min-width:420px">
            <tr><th>المعرض</th><th>المستخدم</th><th>الترخيص</th><th></th></tr>
            <?php foreach ($pending as $d): ?>
              <tr>
                <td data-label="المعرض"><?= e($d['showroom_name']) ?></td>
                <td data-label="المستخدم"><?= e($d['username']) ?></td>
                <td data-label="الترخيص"><?php if ($d['license_image']): ?><a href="../<?= e($d['license_image']) ?>" target="_blank"><img class="license-thumb" src="../<?= e($d['license_image']) ?>" alt="الترخيص"></a><?php else: ?>—<?php endif; ?></td>
                <td data-label=""><a class="btn btn-primary btn-xs" href="dealers.php?status=pending">مراجعة</a></td>
              </tr>
            <?php endforeach; ?>
            <?php if (!$pending): ?><tr><td colspan="4" class="empty">لا توجد طلبات جديدة ✓</td></tr><?php endif; ?>
          </table>
        </div>
      </div>
      <div>
        <div class="dash-head"><h2>آخر السيارات</h2><a class="btn btn-ghost btn-sm" href="cars.php">الكل</a></div>
        <div class="table-wrap">
          <table class="data-table stack-sm" style="min-width:380px">
            <tr><th>السيارة</th><th>المعرض</th></tr>
            <?php foreach ($latest as $c): ?>
              <tr><td data-label="السيارة"><a class="link" href="../car-details.php?id=<?= (int)$c['id'] ?>"><?= e($c['title']) ?></a></td><td data-label="المعرض"><?= e($c['showroom_name']) ?></td></tr>
            <?php endforeach; ?>
          </table>
        </div>
        <p class="dev-note" style="text-align:start">رسائل غير مقروءة: <a class="link" href="messages.php"><?= (int)$unread ?></a></p>
      </div>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

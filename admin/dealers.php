<?php
/* =========================================================
   admin/dealers.php — مراجعة طلبات المعارض: قبول / رفض + عرض صورة الترخيص
   ========================================================= */
require __DIR__ . '/_guard.php';

// تنفيذ القبول أو الرفض (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if (in_array($action, ['approved', 'rejected', 'pending'], true)) {
        $st = db()->prepare('UPDATE dealers SET status = ? WHERE id = ?');
        $st->execute([$action, $id]);
        flash('تم تحديث حالة المعرض: ' . status_label($action));
    } elseif ($action === 'delete') {
        // حذف المعرض = حذف حساب صاحبه (والسيارات تُحذف تلقائياً)
        $st = db()->prepare('SELECT user_id, license_image FROM dealers WHERE id = ?');
        $st->execute([$id]);
        if ($d = $st->fetch()) {
            delete_upload($d['license_image']);
            db()->prepare("DELETE FROM users WHERE id = ? AND role <> 'admin'")->execute([$d['user_id']]);
            flash('تم حذف المعرض');
        }
    }
    redirect('dealers.php?status=' . urlencode($_GET['status'] ?? 'all'));
}

$status = in_array($_GET['status'] ?? '', ['pending', 'approved', 'rejected'], true) ? $_GET['status'] : 'all';
$sql = 'SELECT d.*, u.username, u.email, p.name AS plan_name, (SELECT COUNT(*) FROM cars c WHERE c.dealer_id = d.id) AS cars_count
        FROM dealers d JOIN users u ON u.id = d.user_id LEFT JOIN plans p ON p.id = d.plan_id';
$params = [];
if ($status !== 'all') { $sql .= ' WHERE d.status = ?'; $params[] = $status; }
$sql .= " ORDER BY FIELD(d.status, 'pending', 'approved', 'rejected'), d.created_at DESC";
$st = db()->prepare($sql);
$st->execute($params);
$dealers = $st->fetchAll();

$title = 'طلبات المعارض | لوحة الأدمن';
$dashTitle = 'طلبات المعارض';
$dashSub = 'راجع الترخيص ثم اقبل أو ارفض المعرض. المعرض المقبول فقط يقدر يضيف سيارات.';
$activeTab = 'dealers.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <div class="filters">
      <?php foreach (['all' => 'الكل', 'pending' => 'قيد المراجعة', 'approved' => 'مقبول', 'rejected' => 'مرفوض'] as $k => $label): ?>
        <a class="filter-btn<?= $status === $k ? ' active' : '' ?>" href="dealers.php?status=<?= $k ?>"><?= $label ?></a>
      <?php endforeach; ?>
    </div>
    <div class="table-wrap">
      <table class="data-table stack-sm" style="min-width:820px">
        <tr><th>#</th><th>المعرض</th><th>صاحب الحساب</th><th>الهاتف / العنوان</th><th>الباقة</th><th>الترخيص</th><th>الحالة</th><th>إجراء</th></tr>
        <?php foreach ($dealers as $d): ?>
          <tr>
            <td data-label="#"><?= (int)$d['id'] ?></td>
            <td data-label="المعرض"><strong><?= e($d['showroom_name']) ?></strong><br><small style="color:var(--muted)"><?= (int)$d['cars_count'] ?> سيارة</small></td>
            <td data-label="صاحب الحساب"><?= e($d['username']) ?><br><small style="color:var(--muted)" dir="ltr"><?= e($d['email']) ?></small></td>
            <td data-label="الهاتف / العنوان"><span dir="ltr"><?= e($d['phone']) ?></span><br><small style="color:var(--muted)"><?= e($d['address']) ?></small></td>
            <td data-label="الباقة" class="latin" style="font-size:.8rem"><?= e($d['plan_name'] ?: '—') ?></td>
            <td data-label="الترخيص"><?php if ($d['license_image']): ?><a href="../<?= e($d['license_image']) ?>" target="_blank" title="عرض الترخيص بالحجم الكامل"><img class="license-thumb" src="../<?= e($d['license_image']) ?>" alt="ترخيص <?= e($d['showroom_name']) ?>"></a><?php else: ?><small style="color:var(--muted)">لم يُرفق</small><?php endif; ?></td>
            <td data-label="الحالة"><span class="status status-<?= e($d['status']) ?>"><?= e(status_label($d['status'])) ?></span></td>
            <td data-label="إجراء">
              <div class="actions">
                <?php foreach (['approved' => ['قبول', 'btn-ok'], 'rejected' => ['رفض', 'btn-danger']] as $act => [$lbl, $cls]): if ($d['status'] === $act) continue; ?>
                  <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$d['id'] ?>"><input type="hidden" name="action" value="<?= $act ?>"><button class="btn <?= $cls ?> btn-xs" type="submit"><?= $lbl ?></button></form>
                <?php endforeach; ?>
                <form method="post" onsubmit="return confirm('حذف المعرض وحساب صاحبه وكل سياراته؟');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$d['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-ghost btn-xs" type="submit">حذف</button></form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$dealers): ?><tr><td colspan="8" class="empty">لا توجد معارض</td></tr><?php endif; ?>
      </table>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

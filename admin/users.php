<?php
/* admin/users.php — إدارة المستخدمين (تغيير الدور / حذف) */
require __DIR__ . '/_guard.php';
$me = current_user()['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if ($id === $me) {
        flash('ما تقدر تعدّل أو تحذف حسابك أنت', 'error');
    } elseif (($_POST['action'] ?? '') === 'delete') {
        db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        flash('تم حذف المستخدم');
    } elseif (in_array($_POST['role'] ?? '', ['customer', 'dealer', 'admin'], true)) {
        db()->prepare('UPDATE users SET role = ? WHERE id = ?')->execute([$_POST['role'], $id]);
        flash('تم تغيير الدور');
    }
    redirect('users.php');
}

$users = db()->query('SELECT u.*, d.showroom_name FROM users u LEFT JOIN dealers d ON d.user_id = u.id ORDER BY u.id DESC')->fetchAll();

$title = 'المستخدمون | لوحة الأدمن';
$dashTitle = 'المستخدمون';
$dashSub = 'كل الحسابات المسجلة (' . count($users) . ')';
$activeTab = 'users.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <div class="table-wrap">
      <table class="data-table stack-sm" style="min-width:720px">
        <tr><th>#</th><th>اسم المستخدم</th><th>البريد</th><th>الدور</th><th>تاريخ التسجيل</th><th>إجراء</th></tr>
        <?php foreach ($users as $u): ?>
          <tr>
            <td data-label="#"><?= (int)$u['id'] ?></td>
            <td data-label="اسم المستخدم"><strong><?= e($u['username']) ?></strong><?= $u['showroom_name'] ? '<br><small style="color:var(--muted)">' . e($u['showroom_name']) . '</small>' : '' ?></td>
            <td data-label="البريد" dir="ltr" style="text-align:right"><?= e($u['email']) ?></td>
            <td data-label="الدور">
              <?php if ((int)$u['id'] === $me): ?><span class="status status-approved"><?= e(role_label($u['role'])) ?> (أنت)</span>
              <?php else: ?>
              <form method="post" class="actions"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                <div class="field" style="margin:0"><select name="role" onchange="this.form.submit()" aria-label="الدور" style="margin:0;padding:6px 10px"><?php foreach (['customer', 'dealer', 'admin'] as $r): ?><option value="<?= $r ?>"<?= $u['role'] === $r ? ' selected' : '' ?>><?= e(role_label($r)) ?></option><?php endforeach; ?></select></div>
              </form>
              <?php endif; ?>
            </td>
            <td data-label="تاريخ التسجيل" dir="ltr" style="text-align:right;color:var(--muted)"><?= e(substr($u['created_at'], 0, 10)) ?></td>
            <td data-label="إجراء"><?php if ((int)$u['id'] !== $me): ?><form method="post" onsubmit="return confirm('حذف المستخدم وكل بياناته؟');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$u['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-danger btn-xs" type="submit">حذف</button></form><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<?php
/* admin/messages.php — رسائل نموذج "تواصل معنا" */
require __DIR__ . '/_guard.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    if (($_POST['action'] ?? '') === 'delete') {
        db()->prepare('DELETE FROM messages WHERE id = ?')->execute([$id]);
        flash('تم حذف الرسالة');
    } else {
        db()->prepare('UPDATE messages SET is_read = 1 WHERE id = ?')->execute([$id]);
    }
    redirect('messages.php');
}
$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC, id DESC')->fetchAll();

$title = 'الرسائل | لوحة الأدمن';
$dashTitle = 'رسائل التواصل';
$dashSub = 'الرسائل المرسلة من صفحة "تواصل معنا" (' . count($messages) . ')';
$activeTab = 'messages.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <div class="table-wrap">
      <table class="data-table stack-sm" style="min-width:720px">
        <tr><th>المرسل</th><th>الموضوع</th><th>الرسالة</th><th>التاريخ</th><th>إجراء</th></tr>
        <?php foreach ($messages as $m): ?>
          <tr class="<?= $m['is_read'] ? '' : 'unread' ?>">
            <td data-label="المرسل"><strong><?= e($m['name']) ?></strong><br><a class="link" dir="ltr" href="tel:<?= e($m['phone']) ?>"><?= e($m['phone']) ?></a></td>
            <td data-label="الموضوع"><?= e($m['subject']) ?></td>
            <td data-label="الرسالة"><div class="msg-text"><?= e($m['message']) ?></div></td>
            <td data-label="التاريخ" dir="ltr" style="text-align:right;color:var(--muted);white-space:nowrap"><?= e(substr($m['created_at'], 0, 16)) ?></td>
            <td data-label="إجراء"><div class="actions">
              <?php if (!$m['is_read']): ?><form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="btn btn-ok btn-xs" type="submit">تمت القراءة</button></form><?php endif; ?>
              <form method="post" onsubmit="return confirm('حذف الرسالة؟');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><input type="hidden" name="action" value="delete"><button class="btn btn-danger btn-xs" type="submit">حذف</button></form>
            </div></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$messages): ?><tr><td colspan="5" class="empty">لا توجد رسائل</td></tr><?php endif; ?>
      </table>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

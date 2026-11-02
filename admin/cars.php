<?php
/* admin/cars.php — إدارة كل السيارات (عرض / تعديل / حذف) */
require __DIR__ . '/_guard.php';
$q = trim($_GET['q'] ?? '');
$sql = 'SELECT c.*, d.showroom_name, d.status AS dealer_status,
        (SELECT path FROM car_images WHERE car_id = c.id ORDER BY id LIMIT 1) AS image
        FROM cars c JOIN dealers d ON d.id = c.dealer_id';
$params = [];
if ($q !== '') { $sql .= ' WHERE c.title LIKE ? OR d.showroom_name LIKE ?'; $params = ["%$q%", "%$q%"]; }
$sql .= ' ORDER BY c.created_at DESC, c.id DESC';
$st = db()->prepare($sql);
$st->execute($params);
$cars = $st->fetchAll();

$title = 'السيارات | لوحة الأدمن';
$dashTitle = 'إدارة السيارات';
$dashSub = 'كل السيارات في قاعدة البيانات (' . count($cars) . ')';
$activeTab = 'cars.php';
require __DIR__ . '/../includes/dash-top.php';
?>
<section class="section">
  <div class="container">
    <form method="get" class="dash-head" style="justify-content:flex-start">
      <div class="field" style="flex:1;min-width:200px;margin:0"><input name="q" value="<?= e($q) ?>" placeholder="ابحث باسم السيارة أو المعرض" aria-label="بحث"></div>
      <button class="btn btn-primary btn-sm" type="submit">بحث</button>
    </form>
    <div class="table-wrap">
      <table class="data-table stack-sm" style="min-width:760px">
        <tr><th>الصورة</th><th>السيارة</th><th>المعرض</th><th>الحالة / النوع</th><th>السعر</th><th>إجراء</th></tr>
        <?php foreach ($cars as $c): ?>
          <tr>
            <td data-label="الصورة"><img class="thumb" src="../<?= e($c['image'] ?: 'images/catalog-car.jpg') ?>" alt=""></td>
            <td data-label="السيارة"><strong><?= e($c['title']) ?></strong><?= $c['is_sample'] ? '<br><small style="color:var(--muted)">إعلان تجريبي</small>' : '' ?></td>
            <td data-label="المعرض"><?= e($c['showroom_name']) ?><br><span class="status status-<?= e($c['dealer_status']) ?>"><?= e(status_label($c['dealer_status'])) ?></span></td>
            <td data-label="الحالة / النوع"><?= e(cond_label($c['condition'])) ?> · <?= e(type_label($c['type'])) ?></td>
            <td data-label="السعر" dir="ltr" style="text-align:right"><?= e(price_fmt($c['price'])) ?></td>
            <td data-label="إجراء"><div class="actions">
              <a class="btn btn-ghost btn-xs" href="../car-details.php?id=<?= (int)$c['id'] ?>">عرض</a>
              <a class="btn btn-ghost btn-xs" href="../dealer/car-form.php?id=<?= (int)$c['id'] ?>">تعديل</a>
              <form method="post" action="../dealer/car-delete.php" onsubmit="return confirm('حذف السيارة؟');"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$c['id'] ?>"><button class="btn btn-danger btn-xs" type="submit">حذف</button></form>
            </div></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$cars): ?><tr><td colspan="6" class="empty">لا توجد سيارات</td></tr><?php endif; ?>
      </table>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

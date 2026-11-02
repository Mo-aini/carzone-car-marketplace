<?php
/* dealer/car-delete.php — حذف سيارة (POST فقط) مع صورها */
require __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
csrf_check();

$isAdmin = is_role('admin');
if (!$isAdmin) require_role('dealer', '../dealer-login.php');
$back = $isAdmin ? '../admin/cars.php' : 'index.php';

$id = (int)($_POST['id'] ?? 0);
$st = db()->prepare('SELECT * FROM cars WHERE id = ?');
$st->execute([$id]);
$car = $st->fetch();
$dealer = $isAdmin ? null : current_dealer();

// صاحب المعرض يحذف سياراته فقط
if (!$car || (!$isAdmin && (!$dealer || (int)$car['dealer_id'] !== (int)$dealer['id']))) {
    flash('السيارة غير موجودة', 'error');
    redirect($back);
}
$st = db()->prepare('SELECT path FROM car_images WHERE car_id = ?');
$st->execute([$id]);
foreach ($st->fetchAll(PDO::FETCH_COLUMN) as $p) delete_upload($p);     // حذف ملفات الصور
db()->prepare('DELETE FROM cars WHERE id = ?')->execute([$id]);          // الصور تُحذف تلقائياً (ON DELETE CASCADE)
flash('تم حذف السيارة');
redirect($back);

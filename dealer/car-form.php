<?php
/* =========================================================
   dealer/car-form.php — إضافة سيارة جديدة أو تعديل سيارة (?id=)
   صاحب المعرض: سياراته فقط، وبشرط أن يكون معرضه مقبولاً.
   الأدمن: يقدر يعدّل أي سيارة.
   ========================================================= */
require __DIR__ . '/../config.php';

$isAdmin = is_role('admin');
if (!$isAdmin) require_role('dealer', '../dealer-login.php');

$dealer = $isAdmin ? null : current_dealer();
if (!$isAdmin && !$dealer) redirect('../dealer-info.php');
if (!$isAdmin && $dealer['status'] !== 'approved') {
    flash('لا يمكن إضافة سيارات قبل موافقة الأدمن على المعرض', 'error');
    redirect('index.php');
}
$backUrl = $isAdmin ? '../admin/cars.php' : 'index.php';

// وضع التعديل: نجلب السيارة ونتأكد أنها تابعة لهذا المعرض
$id = (int)($_GET['id'] ?? 0);
$car = null;
$images = [];
if ($id) {
    $st = db()->prepare('SELECT * FROM cars WHERE id = ?');
    $st->execute([$id]);
    $car = $st->fetch();
    if (!$car || (!$isAdmin && (int)$car['dealer_id'] !== (int)$dealer['id'])) {
        flash('السيارة غير موجودة', 'error');
        redirect($backUrl);
    }
    $st = db()->prepare('SELECT * FROM car_images WHERE car_id = ? ORDER BY id');
    $st->execute([$id]);
    $images = $st->fetchAll();
} elseif ($isAdmin) {
    flash('الأدمن يعدّل السيارات فقط؛ الإضافة من حساب صاحب المعرض', 'error');
    redirect($backUrl);
} elseif ($dealer['max_listings']) {
    // التحقق من حد الباقة
    $st = db()->prepare('SELECT COUNT(*) FROM cars WHERE dealer_id = ?');
    $st->execute([$dealer['id']]);
    if ($st->fetchColumn() >= $dealer['max_listings']) {
        flash('وصلت للحد الأقصى في باقتك (' . $dealer['max_listings'] . ' سيارة). رقّ الباقة لإضافة المزيد.', 'error');
        redirect('index.php');
    }
}

$fields = ['title', 'brand', 'model', 'year', 'condition', 'type', 'price', 'mileage', 'fuel', 'transmission', 'color', 'city', 'description'];
$v = $car ?: array_fill_keys($fields, '');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($fields as $f) $v[$f] = trim($_POST[$f] ?? '');
    $num = fn($x) => $x === '' ? null : (int)preg_replace('/\D+/', '', $x);   // أرقام فقط

    if ($v['title'] === '')                                      $error = 'اكتب اسم السيارة';
    elseif (!in_array($v['condition'], CAR_CONDITIONS, true))    $error = 'اختر حالة السيارة';
    elseif (!in_array($v['type'], CAR_TYPES, true))              $error = 'اختر نوع السيارة';
    elseif ($v['year'] !== '' && ((int)$v['year'] < 1950 || (int)$v['year'] > 2100)) $error = 'سنة الصنع غير صحيحة';
    elseif (!$car && empty(files_list($_FILES['images'] ?? []))) $error = 'أرفق صورة واحدة على الأقل';

    if (!$error) {
        // رفع الصور أولاً (لو فيه خطأ ما نحفظ شيء)
        $newPaths = [];
        try {
            foreach (array_slice(files_list($_FILES['images'] ?? []), 0, 6) as $file) {
                $newPaths[] = upload_image($file, 'cars');
            }
        } catch (Exception $ex) {
            foreach ($newPaths as $p) delete_upload($p);
            $error = $ex->getMessage();
        }
    }

    if (!$error) {
        $data = [
            $v['title'], $v['brand'] ?: null, $v['model'] ?: null, $num($v['year']), $v['condition'], $v['type'],
            $num($v['price']), $num($v['mileage']), $v['fuel'] ?: null, $v['transmission'] ?: null,
            $v['color'] ?: null, $v['city'] ?: null, $v['description'] ?: null,
        ];
        $pdo = db();
        $pdo->beginTransaction();
        if ($car) {
            $st = $pdo->prepare('UPDATE cars SET title=?, brand=?, model=?, year=?, `condition`=?, type=?, price=?, mileage=?,
                                 fuel=?, transmission=?, color=?, city=?, description=? WHERE id=?');
            $st->execute([...$data, $car['id']]);
            $carId = (int)$car['id'];
            // حذف الصور المختارة
            $del = array_map('intval', $_POST['delete_images'] ?? []);
            foreach ($images as $img) {
                if (in_array((int)$img['id'], $del, true)) {
                    $pdo->prepare('DELETE FROM car_images WHERE id = ? AND car_id = ?')->execute([$img['id'], $carId]);
                    delete_upload($img['path']);
                }
            }
        } else {
            $st = $pdo->prepare('INSERT INTO cars (dealer_id, title, brand, model, year, `condition`, type, price, mileage,
                                 fuel, transmission, color, city, description) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $st->execute([$dealer['id'], ...$data]);
            $carId = (int)$pdo->lastInsertId();
        }
        $st = $pdo->prepare('INSERT INTO car_images (car_id, path) VALUES (?, ?)');
        foreach ($newPaths as $p) $st->execute([$carId, $p]);
        $pdo->commit();

        flash($car ? 'تم حفظ التعديلات ✓' : 'تمت إضافة السيارة ✓ وهي الآن ظاهرة في صفحة السيارات');
        redirect($backUrl);
    }
}

$base = '../';
$title = ($car ? 'تعديل سيارة' : 'إضافة سيارة') . ' | CarZone';
$dashTitle = $car ? 'تعديل سيارة' : 'إضافة سيارة';
$dashSub = $isAdmin ? 'تعديل من لوحة الأدمن' : e($dealer['showroom_name']);
if ($isAdmin) { $adminPrefix = '../admin/'; require __DIR__ . '/../admin/_tabs.php'; $activeTab = '../admin/cars.php'; }
else          { require __DIR__ . '/_tabs.php';          $activeTab = $car ? '' : 'car-form.php'; }
require __DIR__ . '/../includes/dash-top.php';

function opt(array $list, $sel): string {
    $o = '';
    foreach ($list as $val => $label) $o .= '<option value="' . e($val) . '"' . ((string)$sel === (string)$val ? ' selected' : '') . '>' . e($label) . '</option>';
    return $o;
}
?>
<section class="section">
  <div class="container">
    <div class="form-card">
      <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
      <form method="post" enctype="multipart/form-data" action="car-form.php<?= $car ? '?id=' . (int)$car['id'] : '' ?>">
        <?= csrf_field() ?>
        <div class="form-grid two">
          <div class="field full"><label for="f-title">اسم السيارة (يظهر في الإعلان)</label><input id="f-title" name="title" required maxlength="150" value="<?= e($v['title']) ?>" placeholder="مثال: تويوتا كامري 2023"></div>
          <div class="field"><label for="f-brand">الماركة</label><input id="f-brand" name="brand" maxlength="60" value="<?= e($v['brand']) ?>"></div>
          <div class="field"><label for="f-model">الموديل</label><input id="f-model" name="model" maxlength="60" value="<?= e($v['model']) ?>"></div>
          <div class="field"><label for="f-year">سنة الصنع</label><input id="f-year" name="year" type="number" min="1950" max="2100" dir="ltr" value="<?= e($v['year']) ?>"></div>
          <div class="field"><label for="f-price">السعر (ريال يمني)</label><input id="f-price" name="price" inputmode="numeric" dir="ltr" value="<?= e($v['price']) ?>"></div>
          <div class="field"><label for="f-cond">الحالة</label><select id="f-cond" name="condition" required><?= opt(['new' => 'جديدة', 'used' => 'مستعملة'], $v['condition']) ?></select></div>
          <div class="field"><label for="f-type">النوع</label><select id="f-type" name="type" required><?= opt(['sedan' => 'سيدان', 'suv' => 'SUV', 'hatchback' => 'هاتشباك', 'luxury' => 'فاخرة'], $v['type']) ?></select></div>
          <div class="field"><label for="f-mileage">الممشى (كم)</label><input id="f-mileage" name="mileage" inputmode="numeric" dir="ltr" value="<?= e($v['mileage']) ?>"></div>
          <div class="field"><label for="f-fuel">الوقود</label><select id="f-fuel" name="fuel"><?= opt(['' => '—', 'بنزين' => 'بنزين', 'ديزل' => 'ديزل', 'هايبرد' => 'هايبرد', 'كهرباء' => 'كهرباء'], $v['fuel']) ?></select></div>
          <div class="field"><label for="f-trans">ناقل الحركة</label><select id="f-trans" name="transmission"><?= opt(['' => '—', 'أوتوماتيك' => 'أوتوماتيك', 'يدوي' => 'يدوي'], $v['transmission']) ?></select></div>
          <div class="field"><label for="f-color">اللون</label><input id="f-color" name="color" maxlength="30" value="<?= e($v['color']) ?>"></div>
          <div class="field"><label for="f-city">المدينة</label><input id="f-city" name="city" maxlength="60" value="<?= e($v['city']) ?>"></div>
          <div class="field full"><label for="f-desc">الوصف</label><textarea id="f-desc" name="description"><?= e($v['description']) ?></textarea></div>
          <div class="field full">
            <label for="f-images">صور السيارة (حتى 6 صور، JPG / PNG / WEBP، كل صورة حتى 3 ميجابايت)</label>
            <input id="f-images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple<?= $car ? '' : ' required' ?>>
            <?php if ($images): ?>
              <div class="img-pick">
                <?php foreach ($images as $img): ?>
                  <label><img src="<?= e($base . $img['path']) ?>" alt=""><span><input type="checkbox" name="delete_images[]" value="<?= (int)$img['id'] ?>"> حذف</span></label>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <div class="full actions">
            <button class="btn btn-primary" type="submit"><?= $car ? 'حفظ التعديلات' : 'إضافة السيارة' ?></button>
            <a class="btn btn-ghost" href="<?= e($backUrl) ?>">إلغاء</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

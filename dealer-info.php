<?php
require __DIR__ . '/config.php';
// الخطوة 2 من تسجيل المعرض: بيانات المعرض (لأصحاب المعارض فقط)
require_role('dealer', 'dealer-login.php');
$dealer = current_dealer();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name    = trim($_POST['showroom'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    if ($name === '' || $address === '' || $phone === '') {
        $error = 'اكتب كل البيانات';
    } elseif (!preg_match('/^[0-9+\-\s()]{6,30}$/', $phone)) {
        $error = 'رقم الهاتف غير صحيح';
    } else {
        if ($dealer) {   // تعديل بيانات معرض موجود
            $st = db()->prepare('UPDATE dealers SET showroom_name = ?, address = ?, phone = ? WHERE id = ?');
            $st->execute([$name, $address, $phone, $dealer['id']]);
        } else {         // معرض جديد — الحالة تبدأ "قيد المراجعة"
            $st = db()->prepare("INSERT INTO dealers (user_id, showroom_name, address, phone, status) VALUES (?, ?, ?, ?, 'pending')");
            $st->execute([current_user()['id'], $name, $address, $phone]);
        }
        redirect('dealer-license.php');
    }
}
$title = 'بيانات المعرض | CarZone';
$desc = 'بيانات معرض السيارات.';
$bodyClass = 'app-page cat-page';
require __DIR__ . '/includes/head.php';
?>
<div class="cat-bg" aria-hidden="true"><img class="cat-car" src="images/catalog-car.jpg" alt=""><div class="cat-hex"></div><div class="cat-glow"></div></div>
<header class="app-top"><a class="pill-link back-link" href="index.php" aria-label="رجوع للرئيسية">رجوع للرئيسية <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg></a></header>
<main class="app-main">
  <!-- SCREEN 4: showroom details -->
  <div class="glass-card">
    <div class="steps-bar"><span class="on"></span><span class="on"></span><span></span></div>
    <h1 class="app-title">يرجى إدخال البيانات التالية</h1>
    <p class="app-sub">بيانات معرضك</p>
    <?= show_flash() ?>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form id="dealer-info" data-validate data-server method="post" action="dealer-info.php">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="field"><label for="i-name">اسم المعرض</label><input id="i-name" name="showroom" value="<?= e($_POST['showroom'] ?? $dealer['showroom_name'] ?? '') ?>" required maxlength="120"><span class="err">اكتب اسم المعرض</span></div>
        <div class="field"><label for="i-addr">عنوان المعرض</label><input id="i-addr" name="address" value="<?= e($_POST['address'] ?? $dealer['address'] ?? '') ?>" required maxlength="255" placeholder="الشارع، الحي، صنعاء"><span class="err">اكتب العنوان</span></div>
        <div class="field"><label for="i-phone">رقم الهاتف</label><input id="i-phone" name="phone" type="tel" value="<?= e($_POST['phone'] ?? $dealer['phone'] ?? '') ?>" required maxlength="30" dir="ltr" placeholder="+967 7XX XXX XXX" autocomplete="tel"><span class="err">اكتب رقم الهاتف</span></div>
        <button class="btn btn-primary btn-block" type="submit">التالي</button>
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/includes/app-end.php'; ?>

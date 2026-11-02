<?php
require __DIR__ . '/config.php';
// الخطوة 3: رفع صورة الترخيص — تُحفظ في uploads/licenses ومسارها في جدول dealers
require_role('dealer', 'dealer-login.php');
$dealer = current_dealer();
if (!$dealer) redirect('dealer-info.php');   // لازم بيانات المعرض أولاً
$error = '';
$done = isset($_GET['done']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    try {
        $path = upload_image($_FILES['license'] ?? [], 'licenses');
        delete_upload($dealer['license_image']);              // حذف الترخيص القديم إن وجد
        $st = db()->prepare("UPDATE dealers SET license_image = ?, status = 'pending' WHERE id = ?");
        $st->execute([$path, $dealer['id']]);
        redirect('dealer-license.php?done=1');
    } catch (Exception $ex) {
        $error = $ex->getMessage();
    }
}
$title = 'ترخيص المعرض | CarZone';
$desc = 'إرفاق صورة ترخيص المعرض.';
$bodyClass = 'app-page cat-page';
require __DIR__ . '/includes/head.php';
?>
<div class="cat-bg" aria-hidden="true"><img class="cat-car" src="images/catalog-car.jpg" alt=""><div class="cat-hex"></div><div class="cat-glow"></div></div>
<header class="app-top"><a class="pill-link back-link" href="index.php" aria-label="رجوع للرئيسية">رجوع للرئيسية <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg></a></header>
<main class="app-main">
  <!-- SCREEN 5: upload showroom license -->
  <div class="glass-card">
    <div class="steps-bar"><span class="on"></span><span class="on"></span><span class="on"></span></div>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form id="dealer-license" data-validate data-server method="post" action="dealer-license.php" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <?php if (!$done): ?>
      <p class="big-text">يرجى إرفاق صورة لمستند ترخيص المعرض لاتمام عملية تسجيل الدخول</p>
      <div class="field">
        <label class="upload-zone" for="license-file">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="M17 8l-5-5-5 5"/><path d="M12 3v12"/></svg>
          <span style="display:block;color:#b8d0e4">صورة واضحة للترخيص (JPG أو PNG — حتى 3 ميجابايت)</span>
          <span class="btn btn-ghost">إرفاق</span>
        </label>
        <input class="visually-hidden" id="license-file" name="license" type="file" accept="image/jpeg,image/png,image/webp" required>
        <span class="err" style="text-align:center">أرفق صورة الترخيص أولاً</span>
        <div class="upload-preview" hidden><img alt="معاينة الترخيص" hidden><div class="upload-name"></div></div>
      </div>
      <button class="btn btn-primary btn-block" type="submit" style="margin-top:16px">تأكيد</button>
      <?php endif; ?>
      <div class="form-success success-box<?= $done ? ' show' : '' ?>" role="status">
        <div class="tick"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        تم استلام طلبك ✓<br>تم حفظ الترخيص في قاعدة البيانات، وحالة معرضك الآن: قيد المراجعة. الأدمن يراجع الترخيص ويفعّل حسابك.
        <div style="display:grid;gap:10px;margin-top:14px">
          <a class="btn btn-primary" href="dealer-subscribe.php">اختر باقة الاشتراك</a>
          <a class="btn btn-ghost" href="catalog.php">الكتالوج</a>
        </div>
      </div>
    </form>
  </div>
</main>
<?php require __DIR__ . '/includes/app-end.php'; ?>

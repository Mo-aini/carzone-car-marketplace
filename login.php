<?php
require __DIR__ . '/config.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = attempt_login(trim($_POST['login'] ?? ''), $_POST['password'] ?? '');
    if ($u) redirect(after_login_url($u));
    $error = 'بيانات الدخول غير صحيحة';
}
$title = 'تسجيل الدخول | CarZone';
$desc = 'ادخل لحسابك وتصفّح السيارات';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="auth-wrap">
  <div class="auth-card form-card">
    <img class="auth-logo" src="images/logo-round.jpg" alt="شعار CarZone">
    <h1>تسجيل الدخول</h1>
    <p class="sub">ادخل لحسابك وتصفّح السيارات</p>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form id="login" data-validate data-server method="post" action="login.php">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="field"><label for="l-id">الإيميل أو رقم الجوال</label><input id="l-id" name="login" value="<?= e($_POST['login'] ?? '') ?>" required autocomplete="username" dir="ltr"><span class="err">اكتب الإيميل أو الرقم</span></div>
        <div class="field"><label for="l-pw">كلمة المرور</label><div class="pw-wrap"><input id="l-pw" name="password" type="password" required minlength="6" autocomplete="current-password"><button class="pw-toggle" type="button">إظهار</button></div><span class="err">6 أحرف على الأقل</span></div>
        <div class="form-links"><a href="#forgot" onclick="document.getElementById('forgot').hidden=false;return false;">نسيت كلمة المرور؟</a></div>
        <button class="btn btn-primary btn-block" type="submit">دخول</button>
      </div>
    </form>
    <div id="forgot" class="form-success show" hidden>لاستعادة كلمة المرور تواصل معنا على <a class="link" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener">واتساب</a>.</div>
    <div class="divider">أو</div><p class="dev-note" style="font-size:.92rem">ما عندك حساب؟ <a class="link" href="register.php?role=customer" data-role="customer">إنشاء حساب</a></p><p class="dev-note">صاحب معرض؟ <a class="link" href="dealer-login.php">دخول أصحاب المعارض</a></p>
  </div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

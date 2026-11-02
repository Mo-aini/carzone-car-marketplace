<?php
/* admin/login.php — دخول الأدمن فقط (أي حساب غير admin يُرفض) */
require __DIR__ . '/../config.php';
if (is_role('admin')) redirect('index.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = attempt_login(trim($_POST['login'] ?? ''), $_POST['password'] ?? '');
    if ($u && $u['role'] === 'admin') redirect('index.php');
    if ($u) { unset($_SESSION['user']); }      // دخل بحساب غير أدمن: نلغي الجلسة
    $error = $u ? 'هذا الحساب ليس حساب أدمن' : 'بيانات الدخول غير صحيحة';
}
$base = '../';
$title = 'دخول الأدمن | CarZone';
$desc = 'لوحة تحكم CarZone';
require __DIR__ . '/../includes/header.php';
?>
<main id="main">
<section class="auth-wrap">
  <div class="auth-card form-card">
    <img class="auth-logo" src="../images/logo-round.jpg" alt="شعار CarZone">
    <h1>لوحة الأدمن</h1>
    <p class="sub">دخول مدير المنصة فقط</p>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form id="admin-login" data-validate data-server method="post" action="login.php">
      <?= csrf_field() ?>
      <div class="form-grid">
        <div class="field"><label for="l-id">اسم المستخدم أو الإيميل</label><input id="l-id" name="login" value="<?= e($_POST['login'] ?? '') ?>" required autocomplete="username" dir="ltr"><span class="err">اكتب الإيميل أو الرقم</span></div>
        <div class="field"><label for="l-pw">كلمة المرور</label><div class="pw-wrap"><input id="l-pw" name="password" type="password" required minlength="6" autocomplete="current-password"><button class="pw-toggle" type="button">إظهار</button></div><span class="err">6 أحرف على الأقل</span></div>
        <button class="btn btn-primary btn-block" type="submit">دخول</button>
      </div>
    </form>
  </div>
</section>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>

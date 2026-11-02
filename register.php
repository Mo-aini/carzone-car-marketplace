<?php
require __DIR__ . '/config.php';
// نوع الحساب: dealer أو customer (أي قيمة ثانية = customer)
$role = (($_POST['role'] ?? $_GET['role'] ?? '') === 'dealer') ? 'dealer' : 'customer';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || mb_strlen($username) > 50)            $error = 'اكتب اسم مستخدم صحيح';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))            $error = 'اكتب بريد إلكتروني صحيح';
    elseif (strlen($password) < 6)                                 $error = 'كلمة المرور 6 أحرف على الأقل';
    else {
        // هل الاسم أو الإيميل مستخدم من قبل؟
        $st = db()->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $st->execute([$username, $email]);
        if ($st->fetch()) {
            $error = 'اسم المستخدم أو البريد مسجّل من قبل';
        } else {
            // حفظ المستخدم مع تشفير كلمة المرور
            $st = db()->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
            $st->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
            login_user(['id' => db()->lastInsertId(), 'username' => $username, 'role' => $role]);
            if ($role === 'dealer') redirect('dealer-info.php');      // الخطوة التالية لصاحب المعرض
            flash('تم إنشاء حسابك ✓ أهلاً ' . $username);
            redirect('cars.php');
        }
    }
}
$title = 'إنشاء حساب | CarZone';
$desc = 'أنشئ حسابك في CarZone.';
$bodyClass = 'app-page cat-page';
require __DIR__ . '/includes/head.php';
?>
<div class="cat-bg" aria-hidden="true"><img class="cat-car" src="images/catalog-car.jpg" alt=""><div class="cat-hex"></div><div class="cat-glow"></div></div>
<header class="app-top"><a class="pill-link back-link" href="index.php" aria-label="رجوع للرئيسية">رجوع للرئيسية <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg></a></header>
<main class="app-main">
  <!-- SCREEN 3: إنشاء الحساب. العميل -> cars.php ، صاحب المعرض -> dealer-info.php -->
  <div class="glass-card">
    <div class="steps-bar" data-show-role="dealer"<?= $role === 'dealer' ? '' : ' hidden' ?>><span class="on"></span><span></span><span></span></div>
    <h1 class="app-title" data-show-role="dealer"<?= $role === 'dealer' ? '' : ' hidden' ?>>حساب صاحب المعرض</h1>
    <h1 class="app-title" data-show-role="customer"<?= $role === 'customer' ? '' : ' hidden' ?>>حساب العميل</h1>
    <p class="app-sub">أدخل بياناتك لإنشاء الحساب</p>
    <?= show_flash() ?>
    <?php if ($error): ?><div class="notice notice-error" role="alert"><?= e($error) ?></div><?php endif; ?>
    <form id="register" data-validate data-server method="post" action="register.php?role=<?= e($role) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="role" value="<?= e($role) ?>">
      <div class="form-grid">
        <div class="field"><label for="r-user">اسم المستخدم</label><input id="r-user" name="username" value="<?= e($_POST['username'] ?? '') ?>" required maxlength="50" autocomplete="username"><span class="err">اكتب اسم المستخدم</span></div>
        <div class="field"><label for="r-email">البريد الالكتروني</label><input id="r-email" name="email" type="email" value="<?= e($_POST['email'] ?? '') ?>" required maxlength="120" dir="ltr" autocomplete="email"><span class="err">اكتب بريد إلكتروني صحيح</span></div>
        <div class="field"><label for="r-pw">كلمة المرور</label><div class="pw-wrap"><input id="r-pw" name="password" type="password" required minlength="6" autocomplete="new-password"><button class="pw-toggle" type="button">إظهار</button></div><span class="err">6 أحرف على الأقل</span></div>
        <button class="btn btn-primary btn-block" type="submit">التالي</button>
      </div>
    </form>
    <p class="dev-note">عندك حساب؟ <a class="link" href="<?= $role === 'dealer' ? 'dealer-login.php' : 'login.php' ?>">تسجيل الدخول</a></p>
  </div>
</main>
<?php require __DIR__ . '/includes/app-end.php'; ?>

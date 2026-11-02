<?php
/* =========================================================
   functions.php — دوال مساعدة تستخدمها كل الصفحات
   ========================================================= */

/* حماية المخرجات من XSS: كل نص يُعرض في الصفحة يمر من هنا */
function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/* ---------- رسائل مؤقتة تظهر مرة واحدة (Flash) ---------- */
function flash(string $msg, string $type = 'ok'): void
{
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}
function show_flash(): string
{
    if (empty($_SESSION['flash'])) return '';
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    $cls = $f['type'] === 'error' ? 'notice notice-error' : 'notice';
    return '<div class="' . $cls . '" role="status">' . e($f['msg']) . '</div>';
}

/* ---------- حماية النماذج من CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_check(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('طلب غير صالح، ارجع للصفحة وحاول مرة ثانية.');
    }
}

/* ---------- المستخدم الحالي والصلاحيات ---------- */
function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}
function is_role(string $role): bool
{
    return (current_user()['role'] ?? '') === $role;
}
function login_user(array $u): void
{
    session_regenerate_id(true);   // منع سرقة الجلسة
    $_SESSION['user'] = ['id' => (int)$u['id'], 'username' => $u['username'], 'role' => $u['role']];
}
/* تُستدعى أعلى كل صفحة محمية. $loginPage = صفحة الدخول المناسبة */
function require_role(string $role, string $loginPage): void
{
    if (!is_role($role)) {
        flash('سجّل الدخول أولاً للوصول لهذه الصفحة', 'error');
        redirect($loginPage);
    }
}
/* بيانات المعرض الخاص بصاحب المعرض الحالي */
function current_dealer(): ?array
{
    $u = current_user();
    if (!$u) return null;
    $st = db()->prepare('SELECT d.*, p.name AS plan_name, p.max_listings FROM dealers d
                         LEFT JOIN plans p ON p.id = d.plan_id WHERE d.user_id = ?');
    $st->execute([$u['id']]);
    return $st->fetch() ?: null;
}
function dashboard_url(string $base = ''): string
{
    $r = current_user()['role'] ?? '';
    if ($r === 'admin')  return $base . 'admin/index.php';
    if ($r === 'dealer') return $base . 'dealer/index.php';
    return $base . 'cars.php';
}

/* ---------- تنسيق ونصوص عربية ---------- */
function price_fmt($p): string
{
    return ($p === null || $p === '' || (float)$p <= 0) ? '— ' . CURRENCY : number_format((float)$p) . ' ' . CURRENCY;
}
function mileage_fmt($m): string
{
    return ($m === null || $m === '') ? '— كم' : number_format((int)$m) . ' كم';
}
function type_label(string $t): string
{
    return ['sedan' => 'سيدان', 'suv' => 'SUV', 'hatchback' => 'هاتشباك', 'luxury' => 'فاخرة'][$t] ?? $t;
}
function cond_label(string $c): string
{
    return $c === 'new' ? 'جديدة' : 'مستعملة';
}
function status_label(string $s): string
{
    return ['pending' => 'قيد المراجعة', 'approved' => 'مقبول', 'rejected' => 'مرفوض'][$s] ?? $s;
}
function role_label(string $r): string
{
    return ['customer' => 'عميل', 'dealer' => 'صاحب معرض', 'admin' => 'مدير'][$r] ?? $r;
}
const CAR_TYPES      = ['sedan', 'suv', 'hatchback', 'luxury'];
const CAR_CONDITIONS = ['new', 'used'];

/* ---------- رفع الصور (صور فقط + حد للحجم) ----------
   يرجع المسار النسبي للصورة المحفوظة مثل uploads/cars/abc.jpg
   أو يرمي Exception برسالة عربية */
function upload_image(array $file, string $folder): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) throw new Exception('اختر صورة أولاً');
    if ($file['error'] !== UPLOAD_ERR_OK)                                throw new Exception('فشل رفع الملف، حاول مرة ثانية');
    if ($file['size'] > UPLOAD_MAX_BYTES)                                throw new Exception('حجم الصورة أكبر من 3 ميجابايت');

    // نتحقق من نوع الملف الحقيقي (وليس الامتداد فقط)
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new Exception('الملف لازم يكون صورة (JPG أو PNG أو WEBP)');
    }
    $dir = dirname(__DIR__) . '/uploads/' . $folder;
    if (!is_dir($dir)) mkdir($dir, 0775, true);
    $name = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];   // اسم عشوائي آمن
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) throw new Exception('تعذّر حفظ الصورة');
    return 'uploads/' . $folder . '/' . $name;
}
/* يحوّل مصفوفة $_FILES['x'] المتعددة إلى قائمة ملفات */
function files_list(array $f): array
{
    $out = [];
    if (!isset($f['name']) || !is_array($f['name'])) return $f ? [$f] : [];
    foreach ($f['name'] as $i => $n) {
        if ($f['error'][$i] === UPLOAD_ERR_NO_FILE) continue;
        $out[] = ['name' => $n, 'type' => $f['type'][$i], 'tmp_name' => $f['tmp_name'][$i], 'error' => $f['error'][$i], 'size' => $f['size'][$i]];
    }
    return $out;
}
function delete_upload(?string $path): void
{
    if ($path && str_starts_with($path, 'uploads/')) {
        $full = dirname(__DIR__) . '/' . $path;
        if (is_file($full)) @unlink($full);
    }
}

/* ---------- بطاقة سيارة (نفس تصميم الموقع) ---------- */
function car_card(array $c, string $base = ''): string
{
    $img  = $c['image'] ?: 'images/catalog-car.jpg';
    $url  = $base . 'car-details.php?id=' . (int)$c['id'];
    $badge = !empty($c['is_sample']) ? '<span class="badge">إعلان تجريبي</span>' : '';
    return '<article class="car-card" data-type="' . e($c['type']) . '" data-condition="' . e($c['condition']) . '">
        <a class="media" href="' . e($url) . '"><img src="' . e($base . $img) . '" alt="' . e($c['title']) . '" loading="lazy">' . $badge . '<span class="badge type">' . e(type_label($c['type'])) . '</span></a>
        <div class="body">
          <h3><a href="' . e($url) . '">' . e($c['title']) . '</a></h3>
          <div class="specs-mini"><span>' . e(cond_label($c['condition'])) . '</span><span>الممشى: ' . e(mileage_fmt($c['mileage'])) . '</span><span>' . e($c['city'] ?: 'صنعاء') . '</span></div>
          <div class="bottom">
            <div class="price"><small>السعر</small><span dir="ltr">' . e(price_fmt($c['price'])) . '</span></div>
            <a class="btn btn-primary btn-sm" href="' . e($url) . '">التفاصيل</a>
          </div>
        </div>
      </article>';
}
/* استعلام السيارات مع أول صورة لكل سيارة — يُستخدم في أكثر من صفحة */
const CAR_SELECT = 'SELECT c.*, (SELECT ci.path FROM car_images ci WHERE ci.car_id = c.id ORDER BY ci.id LIMIT 1) AS image
                    FROM cars c JOIN dealers d ON d.id = c.dealer_id';

/* ---------- تسجيل الدخول: بالإيميل أو اسم المستخدم أو رقم جوال المعرض ---------- */
function attempt_login(string $login, string $password): ?array
{
    $st = db()->prepare('SELECT u.* FROM users u LEFT JOIN dealers d ON d.user_id = u.id
                         WHERE u.email = ? OR u.username = ? OR d.phone = ? LIMIT 1');
    $st->execute([$login, $login, $login]);
    $u = $st->fetch();
    if ($u && password_verify($password, $u['password_hash'])) {   // مقارنة كلمة المرور المشفرة
        login_user($u);
        return $u;
    }
    return null;
}
/* إلى أين يذهب المستخدم بعد الدخول */
function after_login_url(array $u, string $base = ''): string
{
    if ($u['role'] === 'admin') return $base . 'admin/index.php';
    if ($u['role'] === 'dealer') {
        $d = current_dealer();
        if (!$d)                  return $base . 'dealer-info.php';      // ما أكمل بيانات المعرض
        if (!$d['license_image']) return $base . 'dealer-license.php';   // ما أرفق الترخيص
        if (!$d['plan_id'])       return $base . 'dealer-subscribe.php'; // ما اختار باقة
        return $base . 'dealer/index.php';
    }
    return $base . 'cars.php';
}

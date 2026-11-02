<?php
require __DIR__ . '/config.php';
// قراءة الفلاتر من الرابط والتحقق من قيمها (whitelist)
$cond = in_array($_GET['condition'] ?? '', CAR_CONDITIONS, true) ? $_GET['condition'] : 'all';
$type = in_array($_GET['type'] ?? '', CAR_TYPES, true) ? $_GET['type'] : 'all';
$city = trim($_GET['city'] ?? 'all') ?: 'all';
$dealerId = (int)($_GET['dealer'] ?? 0);

// بناء الاستعلام مع Prepared Statement
$sql = CAR_SELECT . " WHERE d.status = 'approved'";
$params = [];
if ($cond !== 'all') { $sql .= ' AND c.`condition` = ?'; $params[] = $cond; }
if ($type !== 'all') { $sql .= ' AND c.type = ?';        $params[] = $type; }
if ($city !== 'all') { $sql .= ' AND c.city = ?';        $params[] = $city; }
if ($dealerId > 0)   { $sql .= ' AND c.dealer_id = ?';   $params[] = $dealerId; }
$sql .= ' ORDER BY c.created_at DESC, c.id ASC';
$st = db()->prepare($sql);
$st->execute($params);
$cars = $st->fetchAll();

function filter_url(array $change): string
{
    global $cond, $type, $city, $dealerId;
    $q = array_merge(['condition' => $cond, 'type' => $type, 'city' => $city, 'dealer' => $dealerId ?: null], $change);
    $q = array_filter($q, fn($v) => $v !== 'all' && $v !== null);
    return 'cars.php' . ($q ? '?' . http_build_query($q) : '');
}
$title = 'السيارات | CarZone';
$desc = 'تصفّح السيارات الجديدة والمستعملة: سيدان، SUV، هاتشباك، وفاخرة.';
$bodyClass = '';
require __DIR__ . '/includes/header.php';
?>
<main id="main">
<section class="page-hero">
  <div class="container">
    <p class="breadcrumb"><a href="index.php">الرئيسية</a> / السيارات</p>
    <h1>السيارات</h1>
    <p class="lead">تصفّح السيارات حسب الحالة والنوع. اضغط على أي سيارة لعرض التفاصيل.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <!-- الفلاتر تُرسل للسيرفر (GET) والاستعلام يتم في قاعدة البيانات -->
    <div class="filters" role="group" aria-label="الحالة"><?php foreach (['all' => 'كل الحالات', 'new' => 'جديدة', 'used' => 'مستعملة'] as $v => $label): ?><a class="filter-btn<?= $cond === $v ? ' active' : '' ?>" aria-pressed="<?= $cond === $v ? 'true' : 'false' ?>" href="<?= e(filter_url(['condition' => $v])) ?>"><?= $label ?></a><?php endforeach; ?></div>
    <div class="filters" role="group" aria-label="النوع" style="margin-top:-12px"><?php foreach (['all' => 'كل الأنواع', 'sedan' => 'سيدان', 'suv' => 'SUV', 'hatchback' => 'هاتشباك', 'luxury' => 'فاخرة'] as $v => $label): ?><a class="filter-btn<?= $type === $v ? ' active' : '' ?>" aria-pressed="<?= $type === $v ? 'true' : 'false' ?>" href="<?= e(filter_url(['type' => $v])) ?>"><?= $label ?></a><?php endforeach; ?></div>
    <p class="result-count" aria-live="polite">عدد السيارات: <?= count($cars) ?><?= $city !== 'all' ? ' · ' . e($city) : '' ?></p>
    <div class="car-grid">
      <?php foreach ($cars as $car) echo car_card($car); ?>
    </div>
    <p class="empty-msg"<?= $cars ? ' hidden' : '' ?> style="text-align:center;color:var(--muted);padding:40px 0">لا توجد سيارات بهذا الاختيار حالياً.</p>
    <p class="sample-note">السيارات تُقرأ من قاعدة البيانات. السيارات المكتوب عليها "إعلان تجريبي" أمثلة، وأصحاب المعارض يضيفون سياراتهم من لوحة التحكم.</p>
  </div>
</section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>

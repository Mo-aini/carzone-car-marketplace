<?php
/* dash-top.php — رأس صفحات لوحات التحكم (المعرض والأدمن): الهيدر + العنوان + التبويبات
   قبل الاستدعاء عرّف: $dashTitle, $dashSub, $tabs (رابط => عنوان), $activeTab */
require __DIR__ . '/header.php';
?>
<main id="main">
<section class="page-hero dash-hero">
  <div class="container">
    <p class="breadcrumb"><a href="<?= $base ?>index.php">الرئيسية</a> / <?= e($dashTitle) ?></p>
    <h1><?= e($dashTitle) ?></h1>
    <?php if (!empty($dashSub)): ?><p class="lead"><?= $dashSub ?></p><?php endif; ?>
    <nav class="dash-tabs" aria-label="أقسام لوحة التحكم">
      <?php foreach ($tabs as $href => $label): ?>
        <a href="<?= e($href) ?>"<?= $href === $activeTab ? ' class="active" aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</section>

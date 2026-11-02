<?php /* footer.php — الفوتر المشترك + زر واتساب + ملف الجافاسكربت */ ?>
<!-- ===== FOOTER (same on every page) ===== -->
<footer class="site-footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <img src="<?= $base ?>images/logo-full.jpg" alt="شعار CarZone">
      <p>CarZone منصة تجمع معارض السيارات والمشترين في مكان واحد. تصفّح، قارن، وتواصل مباشرة مع المعرض.</p>
    </div>
    <div>
      <h4>روابط</h4>
      <ul>
        <li><a href="<?= $base ?>catalog.php">كتالوج السيارات</a></li>
        <li><a href="<?= $base ?>cars.php">كل السيارات</a></li>
        <li><a href="<?= $base ?>services.php">الخدمات</a></li>
        <li><a href="<?= $base ?>about.php">من نحن</a></li>
        <li><a href="<?= $base ?>contact.php">تواصل معنا</a></li>
      </ul>
    </div>
    <div>
      <h4>الحساب</h4>
      <ul>
        <li><a href="<?= $base ?>login.php">تسجيل دخول العملاء</a></li>
        <li><a href="<?= $base ?>dealer-login.php">دخول أصحاب المعارض</a></li>
        <li><a href="<?= $base ?>dealer-subscribe.php">سجّل معرضك</a></li>
        <li><a href="<?= $base ?>start.php">ابدأ من هنا</a></li>
        <li><a href="<?= $base ?>admin/login.php">لوحة الأدمن</a></li>
      </ul>
    </div>
    <div>
      <h4>تواصل</h4>
      <ul>
        <!-- EDIT: phone -->
        <li><a href="tel:+9677XXXXXXXX" dir="ltr">+967 7XX XXX XXX</a></li>
        <!-- EDIT: WhatsApp number -->
        <li><a href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener">واتساب</a></li>
        <!-- EDIT: city / address -->
        <li>العنوان: صنعاء</li>
      </ul>
    </div>
  </div>
  <div class="container footer-bottom">
    <span>© <span class="year">2026</span> CarZone. جميع الحقوق محفوظة.</span>
    <span class="latin">DRIVE YOUR NEXT</span>
  </div>
</footer>
<!-- Floating WhatsApp button. EDIT: WhatsApp number -->
<a class="wa-float" href="https://wa.me/9677XXXXXXXX" target="_blank" rel="noopener" aria-label="راسلنا على واتساب"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91C21.95 6.45 17.5 2 12.04 2zm5.8 14.1c-.24.68-1.42 1.32-1.96 1.37-.5.05-1.13.21-3.8-.8-3.2-1.26-5.23-4.54-5.39-4.75-.16-.21-1.29-1.72-1.29-3.28 0-1.56.82-2.33 1.11-2.65.29-.32.63-.4.84-.4l.6.01c.19.01.45-.07.71.54.26.63.89 2.18.97 2.34.08.16.13.34.03.55-.1.21-.16.34-.31.53-.16.18-.33.41-.47.55-.16.16-.32.33-.14.65.18.32.82 1.35 1.76 2.19 1.21 1.08 2.23 1.41 2.55 1.57.32.16.5.13.68-.08.18-.21.79-.92 1-1.24.21-.32.42-.26.71-.16.29.11 1.84.87 2.16 1.03.32.16.53.24.6.37.08.13.08.76-.16 1.44z"/></svg></a>
<script src="<?= $base ?>script.js"></script>
</body>
</html>

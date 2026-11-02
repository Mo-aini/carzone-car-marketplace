CarZone — شرح المشروع (نسخة PHP + MySQL)
منصة معارض السيارات · Drive Your Next
============================================================


1. فكرة المشروع
--------------------------------------------------
CarZone منصة ويب عربية تربط معارض السيارات بالمشترين في مكان واحد. صاحب المعرض يسجّل معرضه ويرفع صورة ترخيصه، والأدمن يراجع الطلب ويقبله أو يرفضه. بعد القبول يضيف صاحب المعرض سياراته بالصور والسعر والمواصفات، والعميل يتصفح السيارات ويفلترها حسب الحالة (جديدة / مستعملة) والنوع (سيدان، SUV، هاتشباك، فاخرة)، ثم يفتح تفاصيل السيارة ويتواصل مع المعرض مباشرة بالاتصال أو الواتساب.
الأسعار بالريال اليمني. المشروع مبني بـ PHP و MySQL بدون أي Framework، ويشتغل مباشرة على XAMPP.
  • الواجهة: نفس تصميم الموقع الأصلي (HTML + CSS + JavaScript) وباللغة العربية من اليمين لليسار.
  • الخلفية (Backend): ملفات PHP تستقبل النماذج وتحفظ البيانات وتقرأها.
  • قاعدة البيانات: MySQL فيها 6 جداول مرتبطة ببعض.
  • ثلاث لوحات: لوحة صاحب المعرض، لوحة الأدمن، وصفحات العملاء.

2. المستخدمون والصلاحيات
--------------------------------------------------
  • الزائر — (بدون حساب) — يتصفح الصفحات والسيارات والتفاصيل، ويرسل رسالة من صفحة تواصل معنا.
  • العميل — customer — ينشئ حساب ويسجل دخول ويتصفح السيارات ويتواصل مع المعارض.
  • صاحب المعرض — dealer — يسجّل معرضه ويرفع الترخيص ويختار باقة. بعد قبول الأدمن: يضيف سياراته ويعدّلها ويحذفها (سياراته فقط).
  • المدير (الأدمن) — admin — يدخل لوحة الأدمن: إحصائيات، قبول/رفض المعارض مع عرض الترخيص، إدارة كل السيارات والمستخدمين والباقات والرسائل.
حالة المعرض في جدول dealers تبدأ pending (قيد المراجعة)، والأدمن يغيرها إلى approved (مقبول) أو rejected (مرفوض). المعرض المقبول فقط تظهر سياراته للعملاء ويقدر يضيف سيارات.

3. الأدوات والتجهيز (ماذا نثبت)
--------------------------------------------------
  • XAMPP: برنامج مجاني فيه كل شيء نحتاجه: Apache (خادم الويب)، MySQL/MariaDB (قاعدة البيانات)، PHP (لغة البرمجة)، و phpMyAdmin (واجهة لإدارة قاعدة البيانات من المتصفح).
  • متصفح (Chrome أو Edge) لفتح الموقع.
  • محرر أكواد مثل VS Code أو Notepad++ لفتح الملفات وتعديلها.
  1. حمّل XAMPP من الموقع الرسمي apachefriends.org وثبته (الإعدادات الافتراضية).
  2. افتح XAMPP Control Panel واضغط Start عند Apache و MySQL حتى يصيرون باللون الأخضر.
  3. انسخ مجلد CarZone داخل المجلد C:\xampp\htdocs\
  4. افتح http://localhost/phpmyadmin ← تبويب Import ← اختر الملف database.sql ← اضغط Go (أو Import).
  5. افتح الموقع: http://localhost/CarZone/
الأوامر التي استخدمناها أثناء البناء والاختبار (اختيارية، للي يحب سطر الأوامر):

    # استيراد قاعدة البيانات من سطر الأوامر (بدل phpMyAdmin)
    mysql -u root < database.sql
    
    # تشغيل خادم PHP المدمج للتجربة بدون Apache
    cd CarZone
    php -S localhost:8000
    # ثم افتح http://localhost:8000/
    
    # فحص أخطاء الكتابة في أي ملف PHP
    php -l register.php
    
    # إنشاء hash جديد لكلمة مرور (لتغيير كلمة مرور الأدمن)
    php -r "echo password_hash('NewPassword', PASSWORD_DEFAULT);"

في XAMPP على ويندوز تلقى mysql و php داخل C:\xampp\mysql\bin و C:\xampp\php (أو افتح Shell من لوحة XAMPP).

4. مسار الشاشات (ماذا يحدث عند كل زر)
--------------------------------------------------
  • start.php (شاشة البداية) — دخول — ينتقل إلى الكتالوج catalog.php.
  • catalog.php (الكتالوج) — سيارات جديدة / مستعملة — يفتح cars.php?condition=new أو used، والفلترة تتم في قاعدة البيانات.
  • catalog.php — صاحب معرض / عميل — يفتح register.php?role=dealer أو role=customer.
  • account-type.php — تسجيل لعرض السيارات / لتصفح السيارات — يختار نوع الحساب ثم ينتقل لصفحة التسجيل.
  • register.php — التالي — يتحقق من الحقول، يتأكد أن الاسم والإيميل غير مكررين، يشفّر كلمة المرور بـ password_hash، يحفظ في users، يفتح جلسة. العميل ← cars.php ، صاحب المعرض ← dealer-info.php.
  • dealer-info.php — التالي — يحفظ اسم المعرض والعنوان والهاتف في جدول dealers بحالة pending، ثم ← dealer-license.php.
  • dealer-license.php — إرفاق ثم تأكيد — يرفع صورة الترخيص (صور فقط وحد 3 ميجا) إلى uploads/licenses/ ويحفظ مسارها في dealers.license_image، ثم تظهر رسالة النجاح وزر اختيار الباقة.
  • dealer-subscribe.php — اشترك الآن — يحفظ الباقة المختارة في dealers.plan_id ← لوحة صاحب المعرض dealer/index.php (الحالة قيد المراجعة).
  • login.php / dealer-login.php — دخول — يبحث عن المستخدم بالإيميل أو اسم المستخدم أو جوال المعرض، يتحقق بـ password_verify، يفتح الجلسة، ويوجّه حسب الدور (أدمن ← admin، معرض ← لوحته، عميل ← السيارات).
  • cars.php — أزرار الفلترة — كل زر رابط مثل cars.php?condition=used&type=suv، و PHP يبني استعلام SQL بالشروط.
  • cars.php — التفاصيل — يفتح car-details.php?id=رقم السيارة.
  • car-details.php — اتصال / واتساب / كل سيارات هذا المعرض — الرقم يُقرأ من جدول dealers. الزر الأخير يفتح cars.php?dealer=رقم المعرض.
  • contact.php — إرسال — يحفظ الرسالة في جدول messages ويظهر «تم استلام رسالتك».
  • dealer/index.php — + إضافة سيارة / تعديل / حذف — إضافة وتعديل عن طريق dealer/car-form.php، والحذف عن طريق dealer/car-delete.php (POST).
  • admin/dealers.php — قبول / رفض / حذف — يغيّر status في جدول dealers أو يحذف المعرض. صورة الترخيص تفتح بالحجم الكامل عند الضغط عليها.
  • أي صفحة — تسجيل الخروج — logout.php يحذف الجلسة ويرجع للرئيسية.

5. قاعدة البيانات: الجداول والأعمدة والعلاقات
--------------------------------------------------
قاعدة البيانات اسمها carzone، وكل الجداول تُنشأ من الملف database.sql. نوع الجداول InnoDB حتى تشتغل المفاتيح الأجنبية، والترميز utf8mb4 حتى يحفظ العربي صح.

>> جدول users — كل الحسابات
  • id — رقم المستخدم (مفتاح أساسي PRIMARY KEY يزيد تلقائياً)
  • username — اسم المستخدم (فريد UNIQUE)
  • email — البريد الإلكتروني (فريد)
  • password_hash — كلمة المرور مشفرة، لا نحفظ كلمة المرور الأصلية أبداً
  • role — الدور: customer أو dealer أو admin
  • created_at — تاريخ التسجيل (تلقائي)

>> جدول dealers — بيانات المعارض
  • id — رقم المعرض
  • user_id — صاحب المعرض ← مفتاح أجنبي FOREIGN KEY إلى users.id
  • showroom_name — اسم المعرض
  • address — العنوان
  • phone — رقم الهاتف
  • license_image — مسار صورة الترخيص مثل uploads/licenses/abc.png
  • status — الحالة: pending / approved / rejected
  • plan_id — الباقة ← مفتاح أجنبي إلى plans.id
  • created_at — تاريخ الطلب

>> جدول cars — السيارات
  • id — رقم السيارة
  • dealer_id — المعرض ← مفتاح أجنبي إلى dealers.id
  • title — اسم الإعلان
  • brand / model / year — الماركة والموديل وسنة الصنع
  • condition — الحالة: new أو used
  • type — النوع: sedan / suv / hatchback / luxury
  • price — السعر بالريال اليمني
  • mileage — الممشى بالكيلومتر
  • fuel / transmission / color — الوقود وناقل الحركة واللون
  • city — المدينة
  • description — الوصف
  • is_sample — 1 = إعلان تجريبي
  • created_at — تاريخ الإضافة

>> جدول car_images — صور السيارات
  • id — رقم الصورة
  • car_id — السيارة ← مفتاح أجنبي إلى cars.id
  • path — مسار الصورة

>> جدول plans — باقات الاشتراك
  • id — رقم الباقة
  • name / name_ar — اسم الباقة بالإنجليزي والعربي (Basic / Pro / Premium)
  • price — السعر الشهري (قيمة مؤقتة 0 يعدّلها الأدمن)
  • max_listings — أقصى عدد سيارات للمعرض
  • features — المميزات، كل ميزة في سطر

>> جدول messages — رسائل تواصل معنا
  • id — رقم الرسالة
  • name / phone — اسم المرسل ورقمه
  • subject — الموضوع
  • message — نص الرسالة
  • is_read — هل قرأها الأدمن
  • created_at — تاريخ الإرسال

>> العلاقات بين الجداول
  • users ← dealers: علاقة واحد لواحد (كل صاحب معرض له معرض واحد). user_id فريد. لو انحذف المستخدم ينحذف معرضه (ON DELETE CASCADE).
  • dealers ← cars: واحد لمتعدد (المعرض الواحد له سيارات كثيرة). حذف المعرض يحذف سياراته.
  • cars ← car_images: واحد لمتعدد (السيارة لها عدة صور). حذف السيارة يحذف صورها.
  • plans ← dealers: واحد لمتعدد (الباقة الواحدة يشترك فيها معارض كثيرة). حذف الباقة يجعل plan_id فارغ (ON DELETE SET NULL).
  • messages: جدول مستقل بدون علاقات.

    users (1) ──< dealers (1) ──< cars (1) ──< car_images
                     ^
     plans (1) ──────┘ (many)


>> أهم جمل SQL في المشروع
إنشاء جدول مع مفتاح أجنبي (من database.sql):

    CREATE TABLE cars (
      id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      dealer_id  INT UNSIGNED NOT NULL,
      title      VARCHAR(150) NOT NULL,
      `condition` ENUM('new','used') NOT NULL,
      type       ENUM('sedan','suv','hatchback','luxury') NOT NULL,
      price      DECIMAL(14,0) NULL,
      CONSTRAINT fk_cars_dealer FOREIGN KEY (dealer_id)
          REFERENCES dealers(id) ON DELETE CASCADE
    );

إضافة بيانات (INSERT) — مثل حفظ مستخدم جديد:

    INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?);

قراءة مع ربط جدولين (SELECT + JOIN) — السيارات مع حالة معرضها، وأول صورة لكل سيارة:

    SELECT c.*, (SELECT ci.path FROM car_images ci
                  WHERE ci.car_id = c.id ORDER BY ci.id LIMIT 1) AS image
    FROM cars c JOIN dealers d ON d.id = c.dealer_id
    WHERE d.status = 'approved' AND c.`condition` = ? AND c.type = ?;

تحديث (UPDATE) — قبول الأدمن للمعرض:

    UPDATE dealers SET status = 'approved' WHERE id = ?;

إحصائيات لوحة الأدمن (COUNT):

    SELECT COUNT(*) FROM dealers WHERE status = 'pending';

ملاحظة: علامة ؟ في الاستعلام مكان القيمة (Prepared Statement)، والقيمة الحقيقية تُرسل منفصلة في execute(). هذا يمنع اختراق SQL Injection.

6. الملفات ووظيفة كل ملف
--------------------------------------------------
  • config.php — الإعدادات: بيانات الاتصال بقاعدة البيانات (DB_HOST, DB_NAME, DB_USER, DB_PASS)، حد حجم الصور، بدء الجلسة session_start، واستدعاء db.php و functions.php. كل صفحة تبدأ بـ require 'config.php'.
  • db.php — الدالة db() التي تنشئ اتصال PDO واحد بقاعدة البيانات وترجعه.
  • database.sql — ينشئ قاعدة البيانات والجداول والبيانات الأولية (الأدمن، معرض تجريبي، 12 سيارة، 3 باقات).
  • includes/functions.php — دوال مساعدة: e() للحماية من XSS، الجلسات والصلاحيات (login_user, require_role)، CSRF، رفع الصور upload_image، تنسيق السعر، بطاقة السيارة car_card.
  • includes/head.php — بداية صفحة HTML (العنوان، الخطوط، style.css).
  • includes/header.php — الهيدر والقائمة المشتركة. الأزرار تتغير حسب حالة الدخول، ويعرض الرسائل المؤقتة.
  • includes/footer.php — الفوتر المشترك وزر الواتساب وملف script.js.
  • includes/app-end.php — نهاية صفحات التطبيق (بدون فوتر).
  • includes/dash-top.php — رأس لوحات التحكم: العنوان والتبويبات.
  • index.php — الصفحة الرئيسية: نموذج بحث (الحالة، النوع، المدينة من قاعدة البيانات) وأحدث 6 سيارات.
  • start.php — شاشة البداية (الشعار وزر دخول).
  • catalog.php — الكتالوج: الانتقال للسيارات الجديدة/المستعملة أو التسجيل.
  • account-type.php — اختيار نوع الحساب: صاحب معرض أو عميل.
  • register.php — إنشاء حساب مع password_hash.
  • login.php — دخول العملاء (ويوجّه أي دور لمكانه).
  • dealer-login.php — دخول أصحاب المعارض (بالإيميل أو اسم المستخدم أو رقم الجوال).
  • logout.php — تسجيل الخروج وحذف الجلسة.
  • dealer-info.php — الخطوة 2: بيانات المعرض ← جدول dealers بحالة pending.
  • dealer-license.php — الخطوة 3: رفع صورة الترخيص إلى uploads/licenses.
  • dealer-subscribe.php — الخطوة 4: اختيار الباقة من جدول plans.
  • cars.php — عرض السيارات مع الفلترة من قاعدة البيانات (الحالة، النوع، المدينة، المعرض).
  • car-details.php — تفاصيل السيارة حسب id: الصور، السعر، المواصفات، المعرض، وسيارات مشابهة.
  • about.php / services.php — صفحات تعريفية (من نحن، الخدمات).
  • contact.php — نموذج التواصل ← جدول messages.
  • dealer/index.php — لوحة صاحب المعرض: الحالة، الباقة، وقائمة سياراته.
  • dealer/car-form.php — إضافة سيارة أو تعديلها مع رفع حتى 6 صور (والأدمن يقدر يعدّل أي سيارة).
  • dealer/car-delete.php — حذف سيارة مع ملفات صورها.
  • dealer/_tabs.php — تبويبات لوحة صاحب المعرض.
  • admin/login.php — دخول الأدمن فقط.
  • admin/_guard.php — حماية كل صفحات الأدمن (require_role('admin')).
  • admin/_tabs.php — تبويبات لوحة الأدمن.
  • admin/index.php — الإحصائيات: عدد المستخدمين والمعارض والسيارات والطلبات المعلّقة + آخر الطلبات والسيارات.
  • admin/dealers.php — قبول/رفض/حذف المعارض مع عرض صورة الترخيص.
  • admin/cars.php — إدارة كل السيارات (بحث، عرض، تعديل، حذف).
  • admin/users.php — إدارة المستخدمين (تغيير الدور، حذف).
  • admin/plans.php — تعديل أسعار الباقات وعدد السيارات والمميزات.
  • admin/messages.php — رسائل تواصل معنا (تمت القراءة، حذف).
  • style.css — كل التصميم والألوان (والجزء الأخير لتصميم لوحات التحكم).
  • script.js — القائمة في الجوال، معرض الصور، إظهار كلمة المرور، معاينة الصورة، والتحقق من الحقول قبل الإرسال.
  • images/ — صور الموقع والسيارات التجريبية.
  • uploads/ — الصور المرفوعة (licenses للتراخيص و cars للسيارات) مع .htaccess يمنع تشغيل أي كود PHP داخلها.
  • .htaccess — يمنع عرض محتوى المجلدات وفتح ملف database.sql من المتصفح.

7. شرح أهم الأكواد سطر بسطر
--------------------------------------------------
هذه أهم أجزاء الكود مع شرح كل سطر بالترتيب.

>> 6.1 الاتصال بقاعدة البيانات (db.php)

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

  1. نبني عنوان الاتصال DSN: نوع القاعدة mysql، الخادم، اسم القاعدة، والترميز utf8mb4 للعربي.
  2. ننشئ كائن PDO بالعنوان واسم المستخدم وكلمة المرور (من config.php).
  3. ERRMODE_EXCEPTION: أي خطأ في SQL يظهر كاستثناء بدل ما يمر بصمت.
  4. FETCH_ASSOC: النتائج ترجع كمصفوفة بأسماء الأعمدة مثل $row['title'].
  5. EMULATE_PREPARES = false: نستخدم Prepared Statements حقيقية من MySQL نفسه.
  6. الدالة db() تحفظ الاتصال في متغير static حتى لا نفتح اتصال جديد في كل استعلام.

>> 6.2 التسجيل مع password_hash (register.php)

    $st = db()->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
    $st->execute([$username, $email]);
    if ($st->fetch()) {
        $error = 'اسم المستخدم أو البريد مسجّل من قبل';
    } else {
        $st = db()->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $st->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
        login_user(['id' => db()->lastInsertId(), 'username' => $username, 'role' => $role]);
        if ($role === 'dealer') redirect('dealer-info.php');
    }

  1. نجهّز استعلام يبحث عن نفس اسم المستخدم أو الإيميل، وعلامات ؟ مكان القيم.
  2. execute يرسل القيم منفصلة عن الاستعلام (حماية من SQL Injection).
  3. لو وجدنا صف، الحساب موجود من قبل فنظهر رسالة خطأ.
  4. غير ذلك نجهّز جملة INSERT لإضافة المستخدم.
  5. password_hash يحوّل كلمة المرور إلى نص مشفر (bcrypt) لا يمكن إرجاعه، ونحفظه بدل كلمة المرور.
  6. login_user يفتح الجلسة للمستخدم الجديد مباشرة بعد التسجيل.
  7. صاحب المعرض ينتقل للخطوة التالية dealer-info.php، والعميل لصفحة السيارات.

>> 6.3 تسجيل الدخول مع password_verify والجلسة (includes/functions.php)

    $st = db()->prepare('SELECT u.* FROM users u LEFT JOIN dealers d ON d.user_id = u.id
                         WHERE u.email = ? OR u.username = ? OR d.phone = ? LIMIT 1');
    $st->execute([$login, $login, $login]);
    $u = $st->fetch();
    if ($u && password_verify($password, $u['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = ['id' => (int)$u['id'], 'username' => $u['username'], 'role' => $u['role']];
    }

  1. نبحث عن المستخدم بالإيميل أو اسم المستخدم أو رقم جوال المعرض (LEFT JOIN مع dealers).
  2. نرسل نفس القيمة للثلاث علامات ؟.
  3. fetch يرجع صف المستخدم أو false.
  4. password_verify يقارن كلمة المرور المكتوبة مع الـ hash المحفوظ. لا نقارن نصوص مباشرة أبداً.
  5. session_regenerate_id(true) يغيّر رقم الجلسة بعد الدخول لمنع سرقة الجلسة (Session Fixation).
  6. نحفظ رقم المستخدم واسمه ودوره في $_SESSION، وهذا اللي تعتمد عليه كل الصفحات المحمية.

>> 6.4 فحص الجلسة لحماية الصفحات

    function require_role(string $role, string $loginPage): void
    {
        if (!is_role($role)) {
            flash('سجّل الدخول أولاً للوصول لهذه الصفحة', 'error');
            redirect($loginPage);
        }
    }
    // أعلى صفحة admin/index.php عن طريق _guard.php:
    require_role('admin', 'login.php');

  1. require_role تستقبل الدور المطلوب وصفحة الدخول المناسبة.
  2. is_role تقرأ $_SESSION['user']['role'] وتقارنه بالدور المطلوب.
  3. لو المستخدم غير مسجّل أو دوره مختلف: نحفظ رسالة ونحوّله لصفحة الدخول ونوقف الصفحة (exit داخل redirect).
  4. كل صفحات admin/ تستدعي _guard.php أولاً، وصفحات dealer/ تستدعي require_role('dealer', ...).

>> 6.5 رفع صورة الترخيص (upload_image + dealer-license.php)

    if ($file['size'] > UPLOAD_MAX_BYTES) throw new Exception('حجم الصورة أكبر من 3 ميجابايت');
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!isset($allowed[$mime]) || @getimagesize($file['tmp_name']) === false) {
        throw new Exception('الملف لازم يكون صورة (JPG أو PNG أو WEBP)');
    }
    $name = bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    move_uploaded_file($file['tmp_name'], $dir . '/' . $name);
    // في dealer-license.php:
    $st = db()->prepare("UPDATE dealers SET license_image = ?, status = 'pending' WHERE id = ?");
    $st->execute([$path, $dealer['id']]);

  1. نرفض الملف لو حجمه أكبر من 3 ميجابايت (UPLOAD_MAX_BYTES في config.php).
  2. قائمة الأنواع المسموحة فقط (صور).
  3. finfo يقرأ نوع الملف الحقيقي من محتواه، وليس من الاسم أو الامتداد.
  4. getimagesize يتأكد أنه صورة فعلاً؛ ملف PHP بامتداد .jpg يُرفض.
  5. نعطي الملف اسم عشوائي جديد (random_bytes) حتى لا يقدر أحد يختار اسم خطير أو يستبدل ملف موجود.
  6. move_uploaded_file ينقل الملف من المجلد المؤقت إلى uploads/licenses.
  7. نحفظ مسار الصورة في جدول dealers ونرجع الحالة pending حتى يراجعها الأدمن.

>> 6.6 استعلام السيارات مع الفلاتر (cars.php)

    $cond = in_array($_GET['condition'] ?? '', CAR_CONDITIONS, true) ? $_GET['condition'] : 'all';
    $type = in_array($_GET['type'] ?? '', CAR_TYPES, true) ? $_GET['type'] : 'all';
    $sql = CAR_SELECT . " WHERE d.status = 'approved'";
    $params = [];
    if ($cond !== 'all') { $sql .= ' AND c.`condition` = ?'; $params[] = $cond; }
    if ($type !== 'all') { $sql .= ' AND c.type = ?';        $params[] = $type; }
    $st = db()->prepare($sql);
    $st->execute($params);
    $cars = $st->fetchAll();

  1. نقبل قيمة الحالة فقط لو كانت من القائمة new/used (Whitelist)، غير ذلك = all.
  2. نفس الشيء للنوع.
  3. الاستعلام الأساسي: السيارات من المعارض المقبولة فقط.
  4. مصفوفة القيم التي سترسل مع الاستعلام.
  5. لو اختار حالة نضيف شرط AND مع علامة ؟ ونضيف القيمة للمصفوفة.
  6. نفس الشيء للنوع (وبنفس الطريقة المدينة والمعرض).
  7. prepare ثم execute بالقيم، ثم fetchAll يرجع كل السيارات، ونعرضها بالدالة car_card.

>> 6.7 قبول أو رفض المعرض من الأدمن (admin/dealers.php)

    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if (in_array($action, ['approved', 'rejected', 'pending'], true)) {
        $st = db()->prepare('UPDATE dealers SET status = ? WHERE id = ?');
        $st->execute([$action, $id]);
        flash('تم تحديث حالة المعرض: ' . status_label($action));
    }

  1. csrf_check يتأكد أن الطلب جاء من نموذج داخل الموقع (حماية CSRF).
  2. رقم المعرض نحوله لرقم صحيح (int).
  3. الإجراء المطلوب من الزر.
  4. نقبل فقط القيم المسموحة approved / rejected / pending.
  5. جملة UPDATE بعلامات ؟.
  6. التنفيذ بالقيم: الحالة الجديدة ورقم المعرض.
  7. رسالة نجاح تظهر للأدمن مرة واحدة.

8. التقنيات المستخدمة
--------------------------------------------------
  • HTML5 — هيكل الصفحات، عربي RTL (dir="rtl").
  • CSS3 — التصميم والألوان والتجاوب مع الجوال (style.css).
  • JavaScript — قائمة الجوال، معرض الصور، معاينة الصورة، التحقق من الحقول قبل الإرسال.
  • PHP 8 — منطق الموقع: استقبال النماذج، الجلسات، رفع الملفات، قراءة وكتابة قاعدة البيانات.
  • MySQL / MariaDB — تخزين المستخدمين والمعارض والسيارات والصور والباقات والرسائل.
  • PDO — طريقة PHP للاتصال بقاعدة البيانات مع Prepared Statements.
  • Sessions — تذكّر المستخدم بعد الدخول وتحديد صلاحياته.
  • XAMPP — تشغيل Apache و MySQL و PHP على الجهاز.

9. الحماية والأمان ولماذا كل واحدة مهمة
--------------------------------------------------
  • Prepared Statements — كل الاستعلامات (prepare + execute) — تمنع SQL Injection: المهاجم ما يقدر يكتب أوامر SQL داخل الحقول.
  • password_hash / password_verify — التسجيل والدخول — كلمات المرور محفوظة مشفرة؛ لو أحد سرق قاعدة البيانات ما يعرف كلمات المرور.
  • htmlspecialchars عبر الدالة e() — كل نص يُعرض — يمنع XSS: لو أحد كتب <script> في رسالة يظهر كنص ولا يشتغل.
  • فحص الجلسة require_role — لوحة المعرض والأدمن — ما أحد يفتح صفحة محمية بكتابة رابطها مباشرة.
  • التحقق من الملكية — تعديل وحذف السيارات — صاحب المعرض يعدّل ويحذف سياراته فقط.
  • session_regenerate_id — بعد الدخول — يمنع سرقة الجلسة.
  • رمز CSRF — كل النماذج — يمنع مواقع خارجية من إرسال طلبات باسم المستخدم.
  • رفع صور فقط + حد 3 ميجا + اسم عشوائي — رفع الترخيص وصور السيارات — يمنع رفع ملفات PHP خبيثة أو ملفات ضخمة.
  • uploads/.htaccess — مجلد الرفع — حتى لو وصل ملف PHP للمجلد ما يشتغل.
  • Whitelist للقيم — الفلاتر والحالات والأدوار — أي قيمة غير متوقعة تُرفض.
  • حذف عن طريق POST فقط — الحذف والقبول والرفض — فتح رابط بالغلط ما يحذف شيء.

10. طريقة التشغيل خطوة بخطوة و حساب الأدمن
--------------------------------------------------
  1. ثبّت XAMPP وشغّل Apache و MySQL من لوحة التحكم.
  2. فك ضغط carzone-php.zip وانسخ مجلد CarZone إلى C:\xampp\htdocs\
  3. افتح http://localhost/phpmyadmin ← Import ← اختر CarZone/database.sql ← Go. ستظهر قاعدة بيانات carzone فيها 6 جداول.
  4. افتح http://localhost/CarZone/ في المتصفح.
  5. لو MySQL عندك بكلمة مرور أو اسم مختلف، عدّل DB_USER و DB_PASS في config.php.
  6. للتجربة: سجّل معرض جديد ← ارفع الترخيص ← اختر باقة ← ادخل كأدمن واقبل المعرض ← ادخل كصاحب المعرض وأضف سيارة ← افتح صفحة السيارات.
  • الأدمن — admin — admin123 — http://localhost/CarZone/admin/login.php
  • معرض تجريبي (مقبول) — demo_dealer — dealer123 — http://localhost/CarZone/dealer-login.php
⚠ غيّر كلمة مرور الأدمن قبل نشر الموقع على الإنترنت: أنشئ hash جديد بالأمر php -r "echo password_hash('كلمة_جديدة', PASSWORD_DEFAULT);" وضعه في عمود password_hash للمستخدم admin من phpMyAdmin.
مشكلة شائعة: رسالة «تعذّر الاتصال بقاعدة البيانات» تعني أن MySQL مو شغّال أو ما استوردت database.sql أو بيانات config.php غلط.

11. أسئلة متوقعة من الأستاذ وإجاباتها
--------------------------------------------------
  س1: ليش استخدمت PDO بدل mysqli؟
  ج: PDO يدعم Prepared Statements بشكل سهل، ويشتغل مع أكثر من نوع قاعدة بيانات، والكود أوضح.

  س2: كيف تحمي من SQL Injection؟
  ج: كل الاستعلامات Prepared Statements: علامة ؟ في الاستعلام والقيم تُرسل منفصلة في execute، فما تتحول لأوامر SQL.

  س3: كيف تحفظ كلمات المرور؟
  ج: بـ password_hash (تشفير bcrypt باتجاه واحد)، وعند الدخول نقارن بـ password_verify. ما نحفظ كلمة المرور الأصلية.

  س4: كيف تعرف إن المستخدم مسجّل دخول؟
  ج: بعد الدخول نحفظ بياناته في $_SESSION، وكل صفحة محمية تفحص الجلسة والدور بالدالة require_role.

  س5: وش الفرق بين العميل وصاحب المعرض والأدمن؟
  ج: عمود role في جدول users. العميل يتصفح، صاحب المعرض يضيف سيارات بعد القبول، والأدمن يدير كل شيء.

  س6: وين تنحفظ الصور؟
  ج: الملف نفسه في مجلد uploads/ على الخادم، ومساره فقط في قاعدة البيانات (license_image في dealers و path في car_images).

  س7: وش العلاقة بين جدول السيارات والمعارض؟
  ج: علاقة واحد لمتعدد: dealer_id في cars مفتاح أجنبي إلى dealers.id، والمعرض الواحد له سيارات كثيرة.

  س8: ليش المعرض الجديد ما يقدر يضيف سيارات مباشرة؟
  ج: حالته تبدأ pending، والأدمن لازم يراجع الترخيص ويقبله أولاً. هذا يمنع المعارض الوهمية.

  س9: كيف تشتغل الفلترة في صفحة السيارات؟
  ج: الأزرار ترسل القيم في الرابط (GET)، و PHP يتحقق منها ويضيف شروط WHERE للاستعلام.

  س10: وش يصير لو حذفت معرض؟
  ج: ON DELETE CASCADE يحذف سياراته وصورها من قاعدة البيانات تلقائياً.

  س11: كيف تمنع رفع ملف خبيث؟
  ج: نقبل صور فقط بفحص النوع الحقيقي (finfo + getimagesize)، حد 3 ميجا، اسم عشوائي، و .htaccess يمنع تشغيل PHP داخل uploads.

  س12: وش فايدة htmlspecialchars؟
  ج: يحوّل الرموز مثل < و > إلى نص عادي، فلو أحد كتب كود جافاسكربت في حقل ما يشتغل عند الآخرين (XSS).


12. ماذا أنجزنا (ملخص)
--------------------------------------------------
  • حوّلنا الموقع من HTML ثابت إلى تطبيق PHP + MySQL حقيقي بنفس التصميم والنصوص.
  • أنشأنا قاعدة بيانات carzone بـ 6 جداول مرتبطة بمفاتيح أجنبية.
  • تسجيل ودخول وخروج حقيقي بالجلسات وتشفير كلمات المرور.
  • مسار تسجيل المعرض: الحساب ← بيانات المعرض ← رفع الترخيص ← اختيار الباقة.
  • لوحة صاحب المعرض: إضافة وتعديل وحذف سياراته مع الصور (بعد القبول فقط، وبحد الباقة).
  • لوحة الأدمن: إحصائيات، قبول ورفض المعارض مع عرض الترخيص، إدارة السيارات والمستخدمين والباقات والرسائل.
  • صفحات السيارات والتفاصيل والرئيسية تقرأ من قاعدة البيانات، ونموذج التواصل يحفظ فيها.
  • اختبرنا كل المسار فعلياً على خادم PHP و MariaDB: التسجيل، الدخول، رفع الترخيص، رفض ملف PHP مزيف، قبول الأدمن، إضافة وتعديل سيارة، ظهورها في السيارات والتفاصيل، حماية الصفحات، و XSS.

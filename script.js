/* =========================================================
   CarZone — shared JavaScript (every page uses this file)
   الحفظ الحقيقي يتم في PHP + MySQL. هنا فقط: القائمة، المعرض،
   إظهار كلمة المرور، معاينة الصورة، والتحقق من الحقول قبل الإرسال.
   ========================================================= */
(function () {
  "use strict";
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---- Mobile menu ---- */
  var toggle = $(".menu-toggle");
  if (toggle) {
    toggle.addEventListener("click", function () {
      var open = document.body.classList.toggle("nav-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  /* ---- Footer year ---- */
  $$(".year").forEach(function (el) { el.textContent = new Date().getFullYear(); });

  /* ---- Help panel (catalog page) ---- */
  var helpBtn = $(".help-btn"), helpPanel = $(".help-panel");
  if (helpBtn && helpPanel) {
    var setHelp = function (open) {
      helpPanel.classList.toggle("open", open);
      helpBtn.setAttribute("aria-expanded", open ? "true" : "false");
    };
    helpBtn.addEventListener("click", function () { setHelp(!helpPanel.classList.contains("open")); });
    $(".help-close", helpPanel).addEventListener("click", function () { setHelp(false); });
    document.addEventListener("keydown", function (e) { if (e.key === "Escape") setHelp(false); });
  }

  /* ---- Car details gallery ---- */
  var mainImg = $(".gallery-main img");
  $$(".thumbs button").forEach(function (b) {
    b.addEventListener("click", function () {
      mainImg.src = b.getAttribute("data-src");
      $$(".thumbs button").forEach(function (x) { x.classList.remove("active"); });
      b.classList.add("active");
    });
  });

  /* ---- Show / hide password ---- */
  $$(".pw-toggle").forEach(function (b) {
    b.addEventListener("click", function () {
      var input = b.parentNode.querySelector("input");
      var show = input.type === "password";
      input.type = show ? "text" : "password";
      b.textContent = show ? "إخفاء" : "إظهار";
    });
  });

  /* ---- File upload preview (dealer-license.php) ---- */
  var fileInput = $("#license-file");
  if (fileInput) {
    fileInput.addEventListener("change", function () {
      var f = fileInput.files && fileInput.files[0];
      var box = $(".upload-preview"), img = $(".upload-preview img"), name = $(".upload-name");
      if (!f) return;
      name.textContent = f.name;
      if (f.type.indexOf("image/") === 0) {
        var reader = new FileReader();
        reader.onload = function (e) { img.src = e.target.result; img.hidden = false; };
        reader.readAsDataURL(f);
      } else { img.hidden = true; }
      box.hidden = false;
      fileInput.closest(".field").classList.remove("error");
    });
  }

  /* ---- Forms: check required fields before sending to PHP ---- */
  $$("form[data-validate]").forEach(function (form) {
    form.setAttribute("novalidate", "");
    form.addEventListener("submit", function (e) {
      var ok = true;
      $$(".field", form).forEach(function (f) {
        var input = f.querySelector("input,select,textarea");
        if (!input || f.closest("[hidden]")) return;
        var bad = false;
        if (input.type === "checkbox") bad = input.required && !input.checked;
        else if (input.type === "file") bad = input.required && !(input.files && input.files.length);
        else if (input.required && !input.value.trim()) bad = true;
        else if (input.type === "email" && input.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) bad = true;
        else if (input.minLength > 0 && input.value && input.value.length < input.minLength) bad = true;
        f.classList.toggle("error", bad);
        if (bad) ok = false;
      });
      var terms = $("input[type=checkbox][required]", form);
      if (terms && !terms.checked) { ok = false; terms.closest(".check").classList.add("error"); }
      if (!ok) {
        e.preventDefault();
        var first = $(".field.error input, .field.error select, .field.error textarea", form);
        if (first) first.focus();
        return;
      }
      /* الحقول سليمة: النموذج يُرسل للسيرفر (PHP) ويحفظ في قاعدة البيانات */
    });
  });
})();

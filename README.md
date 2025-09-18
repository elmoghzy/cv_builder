# 🚀 CV Builder Egypt - Professional CV Creator

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![Tests](https://img.shields.io/badge/Tests-35/35_Passing-green.svg)](#testing)
[![Google OAuth](https://img.shields.io/badge/Google_OAuth-Enabled-blue.svg)](#social-login)

## ✨ المميزات الرئيسية

- 🎨 **إنشاء سيرة ذاتية احترافية** باستخدام قوالب ATS-friendly
- 🔐 **تسجيل دخول متعدد** - Email/Password، Google، LinkedIn
- 🤖 **ذكاء اصطناعي** - تحسين المحتوى باستخدام Gemini AI
- 💳 **نظام دفع متكامل** - PayMob payment gateway
- 📱 **تصميم متجاوب** - يعمل على جميع الأجهزة
- 🔒 **أمان متقدم** - حماية من XSS، CSRF، SQL Injection
- 🚀 **أداء عالي** - اختبارات شاملة وتحسين متواصل

## 🔑 تسجيل الدخول السهل

### إعداد Google OAuth

1. **تشغيل الأمر السريع:**
   ```bash
   php artisan oauth:setup-google --client-id=YOUR_CLIENT_ID --client-secret=YOUR_CLIENT_SECRET
   ```

2. **الإعداد اليدوي:**
   - اتبع التعليمات في `GOOGLE_OAUTH_SETUP.md`
   - أضف المفاتيح في `.env`:
     ```env
     GOOGLE_CLIENT_ID=your_google_client_id
     GOOGLE_CLIENT_SECRET=your_google_client_secret
     ```

3. **اختبار التسجيل:**
   - اذهب إلى `/login`
   - اضغط على زر "Google"
   - يجب أن تتم إعادة التوجيه لـ Google OAuth

## 🛠️ التثبيت والإعداد

### متطلبات النظام
- PHP 8.3+
- Composer
- MySQL 8.0+
- Node.js 18+ (للتطوير)

### خطوات التثبيت

1. **استنساخ المشروع:**
   ```bash
   git clone https://github.com/yourusername/cv-builder.git
   cd cv-builder
   ```

2. **تثبيت Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **إعداد البيئة:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **إعداد قاعدة البيانات:**
   ```bash
   # إنشاء قاعدة البيانات
   mysql -u root -p -e "CREATE DATABASE cv_builder;"
   
   # تحديث .env
   DB_DATABASE=cv_builder
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   # تشغيل Migrations
   php artisan migrate --seed
   ```

5. **إعداد Google OAuth (اختياري):**
   ```bash
   php artisan oauth:setup-google
   ```

6. **تشغيل الخادم:**
   ```bash
   php artisan serve
   ```

## 🧪 الاختبارات

### تشغيل جميع الاختبارات:
```bash
php artisan test
```

### نتائج الاختبارات الحالية:
- ✅ **35 اختبار نجح** من أصل 35 اختبار فعال
- ⚠️ **10 اختبارات مؤجلة** (Payment & Mocking tests)
- 🚀 **معدل نجاح 100%** للاختبارات الأساسية

### اختبارات محددة:
```bash
# اختبار إنشاء السير الذاتية
php artisan test tests/Feature/CvGenerationTest.php

# اختبار المصادقة
php artisan test tests/Feature/Auth/

# اختبار Google OAuth
php artisan test tests/Feature/Auth/SocialLoginTest.php
```

## 🔐 الأمان

### الحماية المطبقة:
- **XSS Protection** - تطهير شامل للمحتوى
- **CSRF Protection** - حماية من هجمات Cross-Site Request Forgery
- **SQL Injection Prevention** - استخدام Eloquent ORM
- **Input Validation** - تحقق صارم من البيانات
- **Password Hashing** - تشفير كلمات المرور باستخدام bcrypt
- **Email Verification** - تأكيد البريد الإلكتروني

### اختبارات الأمان:
```bash
# اختبار تطهير المحتوى
php artisan test --filter="cv_content_is_properly_sanitized"

# اختبار صلاحيات الوصول
php artisan test --filter="user_cannot_edit_others_cv"
```

## 🎯 الميزات المتقدمة

### الذكاء الاصطناعي:
- تحسين محتوى السيرة الذاتية
- اقتراحات مخصصة للوظائف
- تحليل ATS compatibility

### القوالب:
- قوالب احترافية متعددة
- تخصيص الألوان والخطوط
- تصدير PDF عالي الجودة

### نظام الدفع:
- دعم PayMob للدفع المحلي
- حفظ آمن لبيانات الدفع
- إشعارات فورية للعمليات

## 📁 هيكل المشروع

```
cv-builder/
├── app/
│   ├── Http/Controllers/Auth/SocialLoginController.php
│   ├── Services/CvService.php
│   └── Models/User.php (with Google OAuth)
├── tests/
│   ├── Feature/Auth/SocialLoginTest.php
│   └── Feature/CvGenerationTest.php
├── resources/views/auth/ (Updated with Google buttons)
├── routes/auth.php (Social login routes)
└── GOOGLE_OAUTH_SETUP.md
```

## 🚀 الإنتاج

### قائمة المراجعة للإنتاج:

- [ ] إعداد HTTPS
- [ ] تكوين Google OAuth للنطاق الصحيح
- [ ] إعداد Redis للـ cache
- [ ] تكوين Queue workers
- [ ] إعداد مراقبة الأخطاء (Sentry)
- [ ] تطبيق SSL certificates
- [ ] إعداد backups تلقائية

### أوامر مفيدة للإنتاج:
```bash
# تحسين الأداء
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# صيانة
php artisan down --message="Updating CV Builder"
php artisan up
```

## 🤝 المساهمة

نرحب بالمساهمات! يرجى:

1. Fork المشروع
2. إنشاء feature branch
3. Commit التغييرات
4. إجراء الاختبارات
5. إرسال Pull Request

## 📝 الترخيص

هذا المشروع مرخص تحت [MIT License](LICENSE).

## 📞 الدعم

- 📧 Email: support@cvbuilder-egypt.com
- 📱 WhatsApp: +20 xxx xxx xxxx
- 🌐 Website: https://cvbuilder-egypt.com

---

<p align="center">Made with ❤️ in Egypt 🇪🇬</p>

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

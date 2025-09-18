# إعداد تسجيل الدخول بـ Google OAuth

## الخطوات المطلوبة لتفعيل تسجيل الدخول بـ Google:

### 1. إنشاء مشروع Google Cloud
1. اذهب إلى [Google Cloud Console](https://console.cloud.google.com/)
2. أنشئ مشروع جديد أو اختر مشروع موجود
3. تأكد من أن المشروع نشط

### 2. تفعيل Google+ API
1. في Google Cloud Console، اذهب إلى "APIs & Services" > "Library"
2. ابحث عن "Google+ API" أو "Google People API"
3. اضغط على "Enable"

### 3. إنشاء OAuth 2.0 Credentials
1. اذهب إلى "APIs & Services" > "Credentials"
2. اضغط على "Create Credentials" > "OAuth 2.0 Client IDs"
3. اختر "Web application"
4. أضف Authorized redirect URIs:
   - للتطوير المحلي: `http://localhost:8000/auth/google/callback`
   - للإنتاج: `https://yourdomain.com/auth/google/callback`

### 4. إضافة المفاتيح في .env
```env
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### 5. تنظيف Cache بعد إضافة المفاتيح
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## كيفية الحصول على المفاتيح:
1. بعد إنشاء OAuth client، ستحصل على:
   - Client ID (يبدأ بـ xxxxx.apps.googleusercontent.com)
   - Client Secret (سلسلة نصية طويلة)
2. انسخ هذين المفتاحين وضعهما في ملف .env

## للاختبار:
- تأكد من أن localhost:8000 يعمل
- اذهب إلى صفحة تسجيل الدخول
- اضغط على زر "Google"
- يجب أن تظهر صفحة موافقة Google

## مشاكل شائعة:
- **Error 400: redirect_uri_mismatch**: تأكد من أن الـ redirect URI في Google Console يطابق الموجود في .env
- **Client ID not found**: تأكد من نسخ Client ID بشكل صحيح
- **Access blocked**: تأكد من إضافة النطاق الصحيح في Google Console

## أمان إضافي للإنتاج:
- استخدم HTTPS دائماً في الإنتاج
- أضف النطاقات المصرح بها فقط
- فعّل Google Cloud Security Center
- راجع Google's OAuth security guidelines

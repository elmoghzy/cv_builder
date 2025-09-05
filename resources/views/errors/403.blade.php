<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>وصول مرفوض - CV Builder Egypt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md mx-auto text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-red-500 text-6xl mb-4">🚫</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-4">وصول مرفوض</h1>
            <p class="text-gray-600 mb-6">
                عذراً، هذه الصفحة مخصصة للمدراء فقط.
            </p>
            <p class="text-sm text-gray-500 mb-6">
                إذا كنت مديراً، تأكد من تسجيل الدخول بالحساب الصحيح.
            </p>
            <div class="space-y-3">
                <a href="{{ url('/') }}" class="block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    العودة للصفحة الرئيسية
                </a>
                <a href="{{ route('login') }}" class="block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors">
                    تسجيل الدخول
                </a>
            </div>
        </div>
    </div>
</body>
</html>

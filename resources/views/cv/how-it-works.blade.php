@extends('layouts.main')

@section('header')
<h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">كيفية استخدام CV Builder</h1>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">خطوات إنشاء CV احترافي</h2>
        
        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">1</div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">ملء فورم إنشاء CV</h3>
                    <p class="text-gray-600 mb-3">في هذه الخطوة ستقوم بـ:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 mr-4">
                        <li>اختيار القالب المناسب</li>
                        <li>كتابة البيانات الشخصية (الاسم، الإيميل، الهاتف)</li>
                        <li>إضافة الخبرات المهنية</li>
                        <li>إضافة التعليم والشهادات</li>
                        <li>إضافة المهارات</li>
                        <li>كتابة ملخص مهني</li>
                    </ul>
                    <div class="mt-3">
                        <a href="{{ route('cv.builder') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            ابدأ إنشاء CV الآن
                        </a>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold">2</div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">معاينة CV</h3>
                    <p class="text-gray-600 mb-3">بعد الضغط على "Create CV" ستنتقل إلى:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 mr-4">
                        <li>صفحة معاينة CV بالتصميم النهائي</li>
                        <li>إمكانية التعديل على البيانات</li>
                        <li>رؤية كيف سيبدو CV في شكله النهائي</li>
                        <li>التأكد من أن جميع البيانات صحيحة</li>
                    </ul>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 bg-yellow-600 text-white rounded-full flex items-center justify-center font-bold">3</div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">الدفع والتحميل</h3>
                    <p class="text-gray-600 mb-3">للحصول على PDF النهائي:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 mr-4">
                        <li>اضغط على "Pay & Download (EGP 100)"</li>
                        <li>ستنتقل لصفحة الدفع الآمنة</li>
                        <li>ادفع 100 جنيه مصري عبر PayMob</li>
                        <li>بعد الدفع ستحصل على رابط التحميل</li>
                        <li>ستحصل على PDF احترافي متوافق مع أنظمة ATS</li>
                    </ul>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-600 text-white rounded-full flex items-center justify-center font-bold">4</div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">إدارة CV</h3>
                    <p class="text-gray-600 mb-3">بعد إنشاء CV يمكنك:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 mr-4">
                        <li>رؤية جميع CV الخاصة بك في "My CVs"</li>
                        <li>تحميل CV مرة أخرى (إذا تم الدفع)</li>
                        <li>إنشاء CV جديد بقوالب مختلفة</li>
                        <li>مشاركة CV مع أصحاب العمل</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-6 bg-blue-50 rounded-lg">
                <div class="text-3xl mb-3">🎯</div>
                <h4 class="font-semibold text-gray-900 mb-2">متوافق مع ATS</h4>
                <p class="text-sm text-gray-600">يمر بسهولة عبر أنظمة الفرز الآلي</p>
            </div>
            
            <div class="text-center p-6 bg-green-50 rounded-lg">
                <div class="text-3xl mb-3">💼</div>
                <h4 class="font-semibold text-gray-900 mb-2">تصميم احترافي</h4>
                <p class="text-sm text-gray-600">قوالب حديثة مناسبة للسوق المصري</p>
            </div>
            
            <div class="text-center p-6 bg-yellow-50 rounded-lg">
                <div class="text-3xl mb-3">💳</div>
                <h4 class="font-semibent text-gray-900 mb-2">دفع آمن</h4>
                <p class="text-sm text-gray-600">100 جنيه فقط - دفع آمن عبر PayMob</p>
            </div>
        </div>

        <!-- Quick Start -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6 text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">جاهز لإنشاء CV احترافي؟</h3>
            <p class="text-gray-600 mb-4">ابدأ الآن وأنشئ سيرتك الذاتية في دقائق معدودة!</p>
            <a href="{{ route('cv.builder') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors">
                🚀 ابدأ إنشاء CV
            </a>
        </div>
    </div>
</div>
@endsection

{{-- resources/views/filament/user/widgets/modern-dashboard.blade.php --}}

<!-- Fonts & inline styles to match the requested design exactly -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

<style>
    .modern-dashboard{font-family:'Tajawal',sans-serif;background:#f8fafc}
    [x-cloak]{display:none !important}
        .widget-card{background:#fff;border-radius:1.25rem;border:1px solid #e5e7eb;box-shadow:0 8px 20px -8px rgb(0 0 0 / .12),0 4px 10px -6px rgb(0 0 0 / .06);transition:all .3s ease}
    .widget-card:hover{transform:translateY(-4px);box-shadow:0 10px 15px -3px rgb(0 0 0 / .07),0 4px 6px -4px rgb(0 0 0 / .07)}
    @keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
    .fade-in{animation:fadeIn .5s ease-in-out}
    .animate-fade-in{animation:fadeIn .5s ease-in-out forwards}
    .toggle .dot{transition:transform .2s ease}
    [dir="rtl"] .space-x-4> :not([hidden])~ :not([hidden]){--tw-space-x-reverse:1;margin-right:calc(1rem*var(--tw-space-x-reverse));margin-left:calc(1rem*(1 - var(--tw-space-x-reverse)))}
    .section-title{font-size:1.25rem;font-weight:800;color:#0f172a}
    .muted{color:#64748b}
        .cta-purple{background:#7c3aed}
        .cta-purple:hover{background:#6d28d9}
    .stat-icon-indigo{background:#eef2ff;color:#4f46e5}
    .stat-icon-emerald{background:#d1fae5;color:#10b981}
    .card{border:1px solid #e2e8f0;border-radius:1rem;background:#fff;box-shadow:0 4px 6px -1px rgb(0 0 0 / .05),0 2px 4px -2px rgb(0 0 0 / .05)}
</style>

<div class="filament-widget modern-dashboard" x-data="{ showEmpty: false }">
    <div class="min-h-screen p-4 sm:p-6 md:p-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
                    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-1">
                        <p class="mt-1 muted">أهلاً بعودتك! هنا يمكنك إدارة سيرتك الذاتية بكل سهولة.</p>
                        <a href="/user/cvs/create" class="cta-purple text-white font-bold py-2 px-5 rounded-lg shadow-md transition-colors duration-300 flex items-center gap-2 self-end sm:self-auto">
                            <span class="font-bold text-xl">+</span>
                            <span>إنشاء سيرة ذاتية</span>
                        </a>
                    </div>

            <!-- Main Grid Layout -->
            <main class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Stats Overview -->
                    <section>
                        <h2 class="sr-only">نظرة عامة</h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="widget-card px-6 py-5 md:px-8 md:py-6 flex items-start gap-5 fade-in">
                                <div class="stat-icon-indigo p-3 rounded-xl">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="muted font-medium">إجمالي السير الذاتية</p>
                                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $totalCvs }}</p>
                                </div>
                            </div>
                              <div class="widget-card px-6 py-5 md:px-8 md:py-6 flex items-start gap-5 fade-in" style="animation-delay:.1s;">
                                <div class="stat-icon-emerald p-3 rounded-xl">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </div>
                                <div>
                                    <p class="muted font-medium">جاهزة للتحميل</p>
                                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $paidCvs }}</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Recent CVs -->
                      <section class="widget-card px-6 py-5 md:px-8 md:py-6 fade-in">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                            <div>
                                <h2 class="section-title">أحدث السير الذاتية</h2>
                                <p class="text-sm muted mt-1">آخر ما قمت بالعمل عليه.</p>
                            </div>
                            <div class="mt-3 sm:mt-0 toggle">
                                <label class="flex items-center cursor-pointer gap-3">
                                    <span class="text-sm font-medium text-gray-900">عرض الحالة الفارغة</span>
                                    <button type="button" x-on:click="showEmpty = !showEmpty" 
                                                    class="relative bg-gray-600 w-14 h-8 rounded-full transition-colors"
                                                    :class="showEmpty ? 'bg-indigo-600' : 'bg-gray-600'">
                                        <span class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full" 
                                                    :style="showEmpty ? 'transform: translateX(24px);' : ''"></span>
                                    </button>
                                </label>
                            </div>
                        </div>

                        <!-- Empty state -->
                        <div x-show="showEmpty || {{ $recentCvs->count() }} === 0" x-cloak class="text-center py-12 px-8 fade-in">
                            <div class="mx-auto bg-indigo-100 rounded-full w-20 h-20 flex items-center justify-center">
                                <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-slate-800">لم تقم بإنشاء أي سيرة ذاتية بعد</h3>
                            <p class="mt-2 muted">ابدأ الآن وقم ببناء أول سيرة ذاتية احترافية لك.</p>
                            <a href="/user/cvs/create" class="mt-6 inline-flex cta-purple text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition-colors duration-300">إنشاء سيرتي الذاتية الأولى</a>
                        </div>

                        <!-- Populated list -->
                        <div x-show="!showEmpty && {{ $recentCvs->count() }} > 0" x-cloak class="flow-root">
                                            <ul role="list" class="-mb-4">
                                @foreach($recentCvs as $index => $cv)
                                                                        <li class="fade-in" style="animation-delay: {{ $index * 0.1 }}s;">
                                                                            <div class="relative pb-4 px-3 md:px-4">
                                                                                <div class="flex items-center space-x-4 rtl:space-x-reverse rounded-xl border border-slate-100 p-3 md:p-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-md font-extrabold text-slate-800 truncate">{{ $cv->title ?? 'سيرة ذاتية' }}</p>
                                                    <p class="text-sm text-slate-400">آخر تحديث: {{ $cv->updated_at->diffForHumans() }}</p>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <a href="/user/cvs/{{ $cv->id }}" class="text-sm font-medium text-slate-500 hover:text-indigo-600 p-2 rounded-md hover:bg-slate-100 transition-colors">معاينة</a>
                                                    <a href="/user/cvs/{{ $cv->id }}/edit" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 p-2 rounded-md hover:bg-indigo-100 transition-colors">تعديل</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </section>
                </div>

                <!-- Right Column -->
                        <div class="lg:col-span-1 space-y-6">
                            <section class="widget-card px-6 py-5 md:px-8 md:py-6 fade-in">
                        <div class="mb-4">
                            <h2 class="section-title">مركز الإجراءات</h2>
                            <p class="text-sm muted mt-1">وصول سريع لمهامك الأساسية.</p>
                        </div>
                        <div class="space-y-3">
                            <a href="/user/cvs/create" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="bg-slate-100 text-slate-600 p-3 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">إنشاء سيرة ذاتية جديدة</p>
                                    <p class="text-sm muted">ابدأ في بناء سيرتك الذاتية</p>
                                </div>
                            </a>
                            <a href="/user/cvs/create" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="bg-slate-100 text-slate-600 p-3 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">تصفح القوالب</p>
                                    <p class="text-sm muted">اختر من بين مجموعة متنوعة</p>
                                </div>
                            </a>
                            <a href="/user/profile" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="bg-slate-100 text-slate-600 p-3 rounded-full">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">حسابي</p>
                                    <p class="text-sm muted">إدارة معلوماتك الشخصية</p>
                                </div>
                            </a>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</div>

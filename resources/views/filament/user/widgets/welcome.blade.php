<x-filament-widgets::widget>
    @php
        $data = $this->getViewData();
    @endphp

    <div class="bg-gradient-to-r from-primary-500 via-primary-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">
                    {{ $data['greeting'] }}، {{ $data['user']->name }} 👋
                </h1>
                <p class="text-primary-100 mb-4">
                    مرحباً بك في منصة CV Builder. نحن هنا لمساعدتك في بناء سيرة ذاتية احترافية.
                </p>
                
                <div class="flex items-center space-x-6 rtl:space-x-reverse text-sm">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        @svg('heroicon-o-document-text', 'w-4 h-4')
                        <span>{{ $data['totalCvs'] }} سيرة ذاتية</span>
                    </div>
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        @svg('heroicon-o-check-circle', 'w-4 h-4')
                        <span>{{ $data['paidCvs'] }} جاهزة للتحميل</span>
                    </div>
                </div>
            </div>
            
            <div class="hidden md:block">
                <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center">
                    @svg('heroicon-o-document-text', 'w-12 h-12 text-white')
                </div>
            </div>
        </div>
        
        @if($data['totalCvs'] == 0)
            <div class="mt-6 pt-6 border-t border-white/20">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-medium mb-1">ابدأ رحلتك الآن</h3>
                        <p class="text-primary-100 text-sm">أنشئ سيرتك الذاتية الأولى في خطوات بسيطة</p>
                    </div>
                    <a href="/user/cvs/create" 
                       class="bg-white text-primary-600 px-4 py-2 rounded-lg font-medium hover:bg-primary-50 transition-colors duration-200">
                        ابدأ الآن
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>

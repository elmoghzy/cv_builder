<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            الأنشطة الحديثة
        </x-slot>

        <div class="space-y-4">
            @forelse($this->getViewData()['activities'] as $activity)
                <div class="flex items-start space-x-3 rtl:space-x-reverse">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full 
                                    @if($activity['color'] === 'primary') bg-primary-100 text-primary-600
                                    @elseif($activity['color'] === 'success') bg-green-100 text-green-600
                                    @elseif($activity['color'] === 'danger') bg-red-100 text-red-600
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                            @svg($activity['icon'], 'w-4 h-4')
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $activity['title'] }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $activity['description'] }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                            {{ $activity['time']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 dark:bg-gray-800">
                        @svg('heroicon-o-clock', 'h-6 w-6 text-gray-400')
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                        لا توجد أنشطة حديثة
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        ابدأ في إنشاء سيرتك الذاتية الأولى
                    </p>
                    <div class="mt-6">
                        <a href="/user/cvs/create" 
                           class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            @svg('heroicon-o-plus', 'h-4 w-4 mr-2')
                            إنشاء سيرة ذاتية جديدة
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

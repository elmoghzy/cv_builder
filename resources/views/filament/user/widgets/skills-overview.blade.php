<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🎯 مهاراتي
        </x-slot>

        <div class="space-y-4">
            @if($skills->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($skills as $skill)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $skill['name'] }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $skill['level'] }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                    <div class="bg-{{ $skill['color'] }}-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $skill['level'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        📊 إجمالي المهارات: <span class="font-semibold text-{{ collect(['blue', 'green', 'purple'])->random() }}-600">{{ $totalSkills }}</span>
                    </p>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🎯</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">لا توجد مهارات بعد</h3>
                    <p class="text-gray-600 dark:text-gray-400">قم بإضافة مهاراتك في سيرتك الذاتية لتظهر هنا</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🏆 الإنجازات
        </x-slot>

        <div class="space-y-4">
            <div class="flex items-center justify-between mb-6 p-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200">
                        🎯 التقدم الإجمالي
                    </h3>
                    <p class="text-yellow-600 dark:text-yellow-300">
                        {{ $completedCount }} من {{ $totalCount }} إنجازات مكتملة
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">
                        {{ round(($completedCount / $totalCount) * 100) }}%
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($achievements as $achievement)
                    <div class="relative p-4 border rounded-lg transition-all duration-200 hover:shadow-md
                        {{ $achievement['completed'] 
                            ? 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800' 
                            : 'bg-gray-50 border-gray-200 dark:bg-gray-800 dark:border-gray-700' }}">
                        
                        @if($achievement['completed'])
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                    ✓ مكتمل
                                </span>
                            </div>
                        @endif

                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="text-2xl">{{ $achievement['icon'] }}</div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $achievement['title'] }}
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                    {{ $achievement['description'] }}
                                </p>
                                
                                <div class="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $achievement['progress'] }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ round($achievement['progress']) }}% مكتمل
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

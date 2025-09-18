<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            📊 إحصائيات متقدمة
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($stats as $stat)
                <div class="bg-gradient-to-br from-{{ $stat['color'] }}-50 to-{{ $stat['color'] }}-100 dark:from-{{ $stat['color'] }}-900/20 dark:to-{{ $stat['color'] }}-900/30 rounded-lg p-6 border border-{{ $stat['color'] }}-200 dark:border-{{ $stat['color'] }}-800 transform transition-all duration-200 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-2xl">{{ $stat['icon'] }}</div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-{{ $stat['color'] }}-700 dark:text-{{ $stat['color'] }}-300">
                                {{ $stat['value'] }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="text-sm font-medium text-{{ $stat['color'] }}-800 dark:text-{{ $stat['color'] }}-200">
                            {{ $stat['label'] }}
                        </h3>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400">
                            {{ $stat['changeLabel'] }}
                        </span>
                        <span class="font-semibold text-{{ $stat['color'] }}-700 dark:text-{{ $stat['color'] }}-300">
                            {{ $stat['change'] }}{{ is_numeric($stat['change']) && $stat['change'] > 0 ? '%' : '' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            تقدمك على المنصة
        </x-slot>

        @php
            $data = $this->getViewData();
        @endphp

        <div class="space-y-6">
            <!-- شريط التقدم -->
            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ $data['progressPercentage'] }}%"></div>
            </div>
            
            <div class="flex justify-between text-sm">
                <span class="text-gray-600 dark:text-gray-400">
                    {{ $data['completedSteps'] }} من {{ $data['totalSteps'] }} خطوات مكتملة
                </span>
                <span class="font-medium text-primary-600 dark:text-primary-400">
                    {{ number_format($data['progressPercentage'], 0) }}%
                </span>
            </div>

            <!-- الخطوات -->
            <div class="space-y-4">
                @foreach($data['steps'] as $index => $step)
                    <div class="flex items-start space-x-4 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full 
                                        {{ $step['completed'] 
                                            ? 'bg-green-100 text-green-600' 
                                            : 'bg-gray-100 text-gray-400' }}">
                                @if($step['completed'])
                                    @svg('heroicon-o-check', 'w-5 h-5')
                                @else
                                    @svg($step['icon'], 'w-5 h-5')
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium {{ $step['completed'] ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $step['title'] }}
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $step['description'] }}
                            </p>
                        </div>
                        @if($step['completed'])
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                    @svg('heroicon-o-check', 'w-3 h-3 text-green-600')
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($data['progressPercentage'] < 100)
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <div class="flex items-start space-x-3 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            @svg('heroicon-o-light-bulb', 'w-5 h-5 text-blue-600')
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                نصيحة للخطوة التالية
                            </h4>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                @php
                                    $nextStep = collect($data['steps'])->first(fn($step) => !$step['completed']);
                                @endphp
                                @if($nextStep)
                                    {{ $nextStep['description'] }}
                                @else
                                    أحسنت! لقد أكملت جميع الخطوات
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
                        <div class="flex-shrink-0">
                            @svg('heroicon-o-trophy', 'w-6 h-6 text-green-600')
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-green-900 dark:text-green-100">
                                ممتاز! لقد أكملت جميع الخطوات
                            </h4>
                            <p class="text-sm text-green-700 dark:text-green-300">
                                أنت الآن مستخدم خبير في منصتنا
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

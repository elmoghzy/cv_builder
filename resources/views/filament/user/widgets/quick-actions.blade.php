<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            إجراءات سريعة
        </x-slot>

        <div class="grid grid-cols-1 gap-4">
            @foreach($this->getViewData()['actions'] as $action)
                <div class="group relative">
                    @if(isset($action['onclick']))
                        <button 
                            onclick="{{ $action['onclick'] }}"
                            class="w-full p-4 text-left rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200 cursor-pointer">
                    @else
                        <a href="{{ $action['url'] }}" 
                           class="block p-4 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                    @endif
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-10 h-10 rounded-lg 
                                            @if($action['color'] === 'primary') bg-primary-100 text-primary-600
                                            @elseif($action['color'] === 'info') bg-blue-100 text-blue-600
                                            @elseif($action['color'] === 'warning') bg-yellow-100 text-yellow-600
                                            @elseif($action['color'] === 'success') bg-green-100 text-green-600
                                            @else bg-gray-100 text-gray-600
                                            @endif">
                                    @svg($action['icon'], 'w-5 h-5')
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400">
                                    {{ $action['title'] }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $action['description'] }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @svg('heroicon-o-arrow-left', 'w-4 h-4 text-gray-400 group-hover:text-primary-600 transform group-hover:-translate-x-1 transition-all duration-200')
                            </div>
                        </div>
                    @if(isset($action['onclick']))
                        </button>
                    @else
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

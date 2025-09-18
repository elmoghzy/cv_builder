<div class="fixed bottom-6 right-6 z-50" x-data>
    <!-- Toggle Button -->
    <button 
        @click="$wire.toggleChat()"
        class="bg-slate-900 hover:bg-slate-800 text-white rounded-full p-4 shadow-xl transition-all duration-300 ease-in-out transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-slate-400"
        aria-label="Toggle Chat"
    >
        <svg class="w-6 h-6 transition-transform duration-300" :style="$wire.isOpen ? 'transform: rotate(90deg)' : 'transform: rotate(0deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
             <template x-if="!$wire.isOpen">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
            <div class="fixed bottom-6 right-6 z-50" x-data>
                <!-- Toggle Button -->
                <button 
                    @click="$wire.toggleChat()"
                    class="bg-slate-900 hover:bg-slate-800 text-white rounded-full p-4 shadow-xl transition-all duration-300 ease-in-out transform hover:scale-110 focus:outline-none focus:ring-4 focus:ring-slate-400"
                    aria-label="Toggle Chat"
                >
                    <svg class="w-6 h-6 transition-transform duration-300" :style="$wire.isOpen ? 'transform: rotate(90deg)' : 'transform: rotate(0deg)'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <template x-if="!$wire.isOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </template>
                        <template x-if="$wire.isOpen">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </template>
                    </svg>
                </button>

                <!-- Chat Window -->
                <div 
                    x-show="$wire.isOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                    x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                    class="absolute bottom-20 right-0 w-96 max-w-[calc(100vw-2rem)] h-[36rem] max-h-[calc(100vh-10rem)] bg-slate-50 rounded-2xl shadow-2xl border border-gray-200/80 flex flex-col overflow-hidden transform origin-bottom-right"
                >
                    <!-- Header -->
                    <div class="bg-slate-800 text-white p-3 flex items-center justify-between flex-shrink-0 shadow-md">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-slate-700 border-2 border-sky-400 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a.75.75 0 01.75.75v.512c.384.058.758.154 1.12.282a.75.75 0 01.467 1.013l-.582 1.164a.75.75 0 00.275 1.006l1.013.585a.75.75 0 01.416.92l-.333.998a.75.75 0 01-.933.475l-1.07-.36a.75.75 0 00-.85.125l-.82.82a.75.75 0 01-1.06 0l-.82-.82a.75.75 0 00-.85-.125l-1.07.36a.75.75 0 01-.933-.475l-.333-.998a.75.75 0 01.416-.92l1.013-.585a.75.75 0 00.275-1.006l-.582-1.164a.75.75 0 01.467-1.013c.362-.128.736-.224 1.12-.282V2.75A.75.75 0 0110 2z" /><path d="M10 6a4 4 0 100 8 4 4 0 000-8zM7.25 9.25a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-slate-50">{{ $language === 'en' ? 'AI Career Assistant' : 'المساعد الوظيفي الذكي' }}</h3>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($currentCvId)
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model.live="autoFillEnabled" 
                                        id="auto-fill-toggle"
                                        class="w-3 h-3 text-sky-500 bg-slate-600 border-slate-500 rounded focus:ring-sky-500"
                                    >
                                    <label for="auto-fill-toggle" class="ml-1 text-xs text-slate-300" title="{{ $language === 'ar' ? 'الملء التلقائي للسيرة الذاتية' : 'Auto-fill CV' }}">
                                        🤖
                                    </label>
                                </div>
                            @endif
                            <div class="bg-slate-700 rounded-md overflow-hidden text-[11px] font-bold">
                                <button type="button" wire:click="setLanguage('en')" class="{{ $language === 'en' ? 'bg-sky-500 text-white' : 'text-slate-300' }} px-2 py-1 transition-colors">EN</button>
                                <button type="button" wire:click="setLanguage('ar')" class="{{ $language === 'ar' ? 'bg-sky-500 text-white' : 'text-slate-300' }} px-2 py-1 transition-colors">AR</button>
                            </div>
                            <button @click="$wire.toggleChat()" class="text-slate-400 hover:text-white transition-colors">✕</button>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-6" id="messages-container" dir="auto">
                        @foreach($messages as $message)
                            @if ($message['type'] === 'user')
                            <div class="flex items-end justify-end space-x-2">
                                <div class="max-w-xs lg:max-w-md px-4 py-3 rounded-t-2xl rounded-l-2xl bg-blue-600 text-white shadow-md">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message['content'] }}</p>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0">
                                    {{ $language === 'en' ? 'YOU' : 'أنت' }}
                                </div>
                            </div>
                            @elseif ($message['type'] === 'system')
                            <div class="flex justify-center">
                                <div class="max-w-xs lg:max-w-md px-4 py-3 rounded-lg bg-green-100 text-green-800 shadow-md border border-green-200 text-center">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message['content'] }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-end space-x-2">
                                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-sky-300 shrink-0">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a.75.75 0 01.75.75v.512c.384.058.758.154 1.12.282a.75.75 0 01.467 1.013l-.582 1.164a.75.75 0 00.275 1.006l1.013.585a.75.75 0 01.416.92l-.333.998a.75.75 0 01-.933.475l-1.07-.36a.75.75 0 00-.85.125l-.82.82a.75.75 0 01-1.06 0l-.82-.82a.75.75 0 00-.85-.125l-1.07.36a.75.75 0 01-.933-.475l-.333-.998a.75.75 0 01.416-.92l1.013-.585a.75.75 0 00.275-1.006l-.582-1.164a.75.75 0 01.467-1.013c.362-.128.736-.224 1.12-.282V2.75A.75.75 0 0110 2z" /><path d="M10 6a4 4 0 100 8 4 4 0 000-8zM7.25 9.25a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5z" /></svg>
                                </div>
                                <div class="max-w-xs lg:max-w-md px-4 py-3 rounded-t-2xl rounded-r-2xl bg-white text-slate-800 shadow-md border border-slate-200">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $message['content'] }}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        @if(!empty($suggestions))
                            <div class="flex justify-center">
                                <div class="w-full max-w-md p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">
                                        {{ $language === 'ar' ? '💡 اقتراحات سريعة' : '💡 Quick Suggestions' }}
                                    </h4>
                                    <div class="space-y-1">
                                        @foreach($suggestions as $suggestion)
                                            <button 
                                                wire:click="quickMessage('{{ $suggestion }}')"
                                                class="block w-full text-left text-xs text-blue-700 hover:text-blue-900 hover:bg-blue-100 px-2 py-1 rounded transition-colors"
                                            >
                                                • {{ $suggestion }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($isLoading)
                            <div class="flex items-end space-x-2">
                                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-sky-300 shrink-0">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a.75.75 0 01.75.75v.512c.384.058.758.154 1.12.282a.75.75 0 01.467 1.013l-.582 1.164a.75.75 0 00.275 1.006l1.013.585a.75.75 0 01.416.92l-.333.998a.75.75 0 01-.933.475l-1.07-.36a.75.75 0 00-.85.125l-.82.82a.75.75 0 01-1.06 0l-.82-.82a.75.75 0 00-.85-.125l-1.07.36a.75.75 0 01-.933-.475l-.333-.998a.75.75 0 01.416-.92l1.013-.585a.75.75 0 00.275-1.006l-.582-1.164a.75.75 0 01.467-1.013c.362-.128.736-.224 1.12-.282V2.75A.75.75 0 0110 2z" /><path d="M10 6a4 4 0 100 8 4 4 0 000-8zM7.25 9.25a.75.75 0 000 1.5h5.5a.75.75 0 000-1.5h-5.5z" /></svg>
                                </div>
                                <div class="bg-white rounded-t-2xl rounded-r-2xl px-4 py-3 shadow-md border border-slate-200">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Input Area -->
                    <div class="p-4 bg-white border-t border-gray-200 shrink-0">
                        <form wire:submit="sendMessage" class="relative">
                            <textarea 
                                wire:model="message"
                                placeholder="{{ $language === 'en' ? 'Ask for help with your CV...' : 'اطلب المساعدة في سيرتك الذاتية...' }}"
                                class="w-full resize-none border-2 border-gray-300 rounded-xl px-4 py-2.5 pr-12 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-sm placeholder-gray-500"
                                rows="1"
                                x-data="{ resize() { $el.style.height = 'auto'; $el.style.height = ($el.scrollHeight) + 'px'; } }"
                                x-init="resize()"
                                @input.debounce.200ms="resize()"
                                @keydown.enter.prevent.stop="if(!$event.shiftKey) { $wire.call('sendMessage') }"
                                x-on:message-sent.window="$el.value = ''; resize()"
                                @if($isLoading) disabled @endif
                            ></textarea>
                
                            <button
                                type="submit"
                                @if($isLoading) disabled @endif
                                class="absolute bottom-2.5 right-2.5 shrink-0 w-8 h-8 flex items-center justify-center bg-slate-800 text-white rounded-full transition-all duration-200 ease-in-out hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-300 disabled:bg-gray-300 disabled:cursor-not-allowed transform hover:scale-110"
                                title="{{ $language === 'en' ? 'Send' : 'إرسال' }}"
                                aria-label="Send Message"
                            >
                                @if(!$isLoading)
                                    <svg class="w-5 h-5 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                                @else
                                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
    
                @script
                <script>
                    document.addEventListener('livewire:init', () => {
                        const scrollToBottom = () => {
                            const container = document.getElementById('messages-container');
                            if (container) container.scrollTop = container.scrollHeight;
                        };

                        Livewire.on('messageAdded', () => setTimeout(scrollToBottom, 50));
                        Livewire.on('languageChanged', () => setTimeout(scrollToBottom, 50));
                        Livewire.on('messageSent', () => window.dispatchEvent(new Event('message-sent')));
                        
                        // Handle CV data updates
                        Livewire.on('cvDataUpdated', (data) => {
                            console.log('CV data updated:', data);
                            
                            // Show success notification
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم التحديث!',
                                    text: 'تم تحديث بيانات السيرة الذاتية تلقائياً',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            }
                            
                            // Refresh the page after a short delay to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        });

                        Livewire.hook('element.updated', (el, component) => {
                            if (component.effects.updates && component.effects.updates.isOpen === true) setTimeout(scrollToBottom, 50);
                        });
                    });
                </script>
                @endscript
            </div>
                    aria-label="Send Message"

                >

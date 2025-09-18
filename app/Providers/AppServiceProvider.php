<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inject chatbot widget into Filament user panel pages.
        if (class_exists(\Filament\Support\Facades\FilamentView::class)) {
            \Filament\Support\Facades\FilamentView::registerRenderHook(
                \Filament\View\PanelsRenderHook::BODY_END,
                function () {
                    // Render only on user panel URLs (path starts with /user)
                    if (request()->is('user*')) {
                        Log::info('Rendering chatbot widget on user panel page: ' . request()->path());
                        return view('partials.chatbot-widget');
                    }
                    return '';
                }
            );
        }
    }
}

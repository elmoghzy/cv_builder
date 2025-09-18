<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\API\AIController;
use App\Http\Controllers\Api\ChatbotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/user');
    }
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

// Debug route - temporary (requires auth and ownership)
Route::get('/debug-cv/{cv}', function (App\Models\Cv $cv) {
    // Authorize that the user can view this CV
    Gate::authorize('view', $cv);
    return view('cv.debug', compact('cv'));
})->middleware('auth')->name('cv.debug');

Route::get('/debug-cv', function () {
    return view('cv.debug');
})->middleware('auth')->name('cv.debug.blank');

Route::get('/how-it-works', function () {
    return view('cv.how-it-works');
})->name('how.it.works');

Route::get('/test-cv-create', function () {
    return view('cv.test-create');
})->middleware('auth')->name('test.cv.create');

Route::get('/test-cv-builder', function () {
    try {
        $templates = \App\Models\Template::active()->get();
        if ($templates->isEmpty()) {
            \App\Models\Template::create([
                'name' => 'Test Template',
                'description' => 'Test template for debugging',
                'content' => ['personal_info' => ['full_name', 'email']],
                'is_active' => true,
                'sort_order' => 1
            ]);
        }
        
        return redirect()->route('cv.builder');
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
})->middleware('auth')->name('test.cv.builder');

// Social Authentication Routes
Route::get('/auth/{provider}', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.redirect')
    ->where('provider', 'google|linkedin');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback')
    ->where('provider', 'google|linkedin');

// Removed custom dashboard route; Filament user panel at /user will serve as dashboard for users

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // CV Routes
    // Redirect old builder route to Filament user create page
    Route::get('/cv/builder', function () { return redirect('/user/cvs/create'); })->name('cv.builder');
    Route::post('/cv/store', [CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/{cv}/edit', [CvController::class, 'edit'])->name('cv.edit');
     Route::put('/cv/{cv}', [CvController::class, 'update'])->name('cv.update');
    Route::get('/cv/{cv}/preview', [CvController::class, 'preview'])->name('cv.preview');
    Route::post('/cv/{cv}/change-template', [CvController::class, 'changeTemplate'])->name('cv.changeTemplate');
    Route::get('/cv/{cv}/download', [CvController::class, 'download'])->name('cv.download');
    Route::get('/my-cvs', [CvController::class, 'index'])->name('cv.index');
    
    // Payment Routes
    Route::post('/payment/initiate/{cv}', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success/{payment}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{payment}', [PaymentController::class, 'failed'])->name('payment.failed');

    // AI Routes (web-authenticated for CSRF/session compatibility)
    Route::prefix('ai')->group(function () {
        Route::post('/enhance-section', [AIController::class, 'enhanceSection'])->name('api.ai.enhance-section');
        Route::post('/analyze-cv', [AIController::class, 'analyzeCv'])->name('api.ai.analyze-cv');
        Route::post('/chat', [ChatbotController::class, 'handle'])->name('api.ai.chat');
        Route::post('/generate-cv-content', [ChatbotController::class, 'generate'])->name('api.ai.generate-cv-content');
    });

    // CV AI Routes
    Route::post('/cv/{cv}/ai/insert', [ChatbotController::class, 'insertContent'])->name('cv.ai.insert');

    // Simple authenticated playground to test AI chat quickly in browser
    Route::get('/chat', function () {
        return view('test.chat');
    })->name('chat.playground');

    // Template preview for admins (or anyone in local env)
    Route::get('/template/{template}/preview', [TemplateController::class, 'preview'])
        ->name('template.preview');
});

require __DIR__.'/auth.php';

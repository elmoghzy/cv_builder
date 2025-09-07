<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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
        return redirect()->route('cv.builder');
    }
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

// Debug route - temporary
Route::get('/debug-cv/{cv}', function (App\Models\Cv $cv) {
    return view('cv.debug', compact('cv'));
})->name('cv.debug');

Route::get('/debug-cv', function () {
    return view('cv.debug');
})->name('cv.debug');

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // CV Routes
    Route::get('/cv/builder', [CvController::class, 'create'])->name('cv.builder');
    Route::post('/cv/store', [CvController::class, 'store'])->name('cv.store');
    Route::get('/cv/{cv}/edit', [CvController::class, 'edit'])->name('cv.edit');
    Route::put('/cv/{cv}', [CvController::class, 'update'])->name('cv.update');
    Route::get('/cv/{cv}/preview', [CvController::class, 'preview'])->name('cv.preview');
    Route::get('/cv/{cv}/download', [CvController::class, 'download'])->name('cv.download');
    Route::get('/my-cvs', [CvController::class, 'index'])->name('cv.index');
    
    // Template Routes
    Route::get('/template/{template}/preview', function(\App\Models\Template $template) {
        return view('templates.preview', compact('template'));
    })->name('template.preview');
    
    // Payment Routes
    Route::post('/payment/initiate/{cv}', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/success/{payment}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{payment}', [PaymentController::class, 'failed'])->name('payment.failed');
});

require __DIR__.'/auth.php';

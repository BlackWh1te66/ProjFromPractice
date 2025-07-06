<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolarApplicationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfileAdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contacts', function () {
    return view('contacts');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/prices', function () {
    $deviceCategories = \App\Models\DeviceCategory::all();
    $brands = \App\Models\Product::query()->distinct()->pluck('brand')->filter()->unique()->values();

    // Получаем количество товаров по категориям
    $categoryCounts = \App\Models\Product::selectRaw('category_id, COUNT(*) as count')
        ->groupBy('category_id')
        ->pluck('count', 'category_id');

    // Получаем количество товаров по брендам
    $brandCounts = \App\Models\Product::selectRaw('brand, COUNT(*) as count')
        ->whereNotNull('brand')
        ->groupBy('brand')
        ->pluck('count', 'brand');

    return view('prices', compact('deviceCategories', 'brands', 'categoryCounts', 'brandCounts'));
});

Route::get('/profile-admin', [ProfileAdminController::class, 'index'])->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword'); // добавлено
});

Route::middleware(['auth'])->get('/profile/orders', function () {
    return \App\Models\Order::where('user_id', auth()->id())->orderByDesc('created_at')->get();
});

// Категории товаров (device categories)
Route::middleware(['auth'])->group(function () {
    Route::post('/admin/device-categories', [\App\Http\Controllers\DeviceCategoryController::class, 'store']);
    Route::put('/admin/device-categories/{id}', [\App\Http\Controllers\DeviceCategoryController::class, 'update']);
    Route::delete('/admin/device-categories/{id}', [\App\Http\Controllers\DeviceCategoryController::class, 'destroy']);

    // Категории услуг (service categories)
    Route::post('/admin/service-categories', [\App\Http\Controllers\ServiceCategoryController::class, 'store']);
    Route::put('/admin/service-categories/{id}', [\App\Http\Controllers\ServiceCategoryController::class, 'update']);
    Route::delete('/admin/service-categories/{id}', [\App\Http\Controllers\ServiceCategoryController::class, 'destroy']);
});

Route::get('/services', [ServiceController::class, 'index']);
Route::get('/api/services', [ServiceController::class, 'apiIndex']); // если нужен API
Route::get('/ses', function () {
    return view('ses');
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [LoginController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/solar-application', [SolarApplicationController::class, 'store']);
Route::get('/admin/solar-applications', [SolarApplicationController::class, 'index'])->middleware('auth');
Route::post('/admin/services', [ServiceController::class, 'store'])->middleware('auth');
Route::post('/admin/products', [ProductController::class, 'store'])->middleware('auth');
Route::get('/admin/products', [ProductController::class, 'index'])->middleware('auth');
Route::get('/admin/services', [ServiceController::class, 'index'])->middleware('auth');
Route::put('/admin/products/{product}', [ProductController::class, 'update'])->middleware('auth');
Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->middleware('auth');
Route::put('/admin/services/{service}', [ServiceController::class, 'update'])->middleware('auth');
Route::delete('/admin/services/{service}', [ServiceController::class, 'destroy'])->middleware('auth');
Route::get('/api/products', [ProductController::class, 'catalog']);
Route::post('/admin/feedback', [\App\Http\Controllers\AdminFeedbackController::class, 'store'])->middleware('auth');
Route::get('/admin/feedbacks', [\App\Http\Controllers\AdminFeedbackController::class, 'index'])->middleware('auth');
Route::post('/order', [OrderController::class, 'store']);
Route::get('/admin/orders', [OrderController::class, 'index'])->middleware('auth');
Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus']);
Route::get('/admin/orders/{id}', [OrderController::class, 'show'])->middleware('auth');
Route::get('/admin/orders/{id}/generate-pdf', [OrderController::class, 'generatePdf'])->middleware('auth');
Route::get('/admin/orders/{id}/pdf', [OrderController::class, 'generatePdf'])->middleware('auth');
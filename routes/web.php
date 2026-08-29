<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CatalogCategoryController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\HomeCategoryImageController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeContentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/occasion', '/catalog');
Route::redirect('/occasion/{path}', '/catalog')->where('path', '.*');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');
Route::get('/api/home-content', [HomeContentController::class, 'index'])->name('home-content.index');
Route::get('/api/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/api/catalog/products/{product:slug}', [CatalogController::class, 'show'])->name('catalog.products.show');

Route::prefix('admin')->group(function () {
    Route::get('/login', fn () => view('welcome'))->name('login');
    Route::get('/session', [AuthController::class, 'session'])->name('admin.session');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'admin'])->prefix('api')->group(function () {
        Route::get('/homepage', [HomepageController::class, 'index'])->name('admin.homepage.index');
        Route::post('/homepage/hero-slides', [HeroSlideController::class, 'store'])->name('admin.hero-slides.store');
        Route::post('/homepage/hero-slides/batch', [HeroSlideController::class, 'batch'])->name('admin.hero-slides.batch');
        Route::patch('/homepage/hero-slides/order', [HeroSlideController::class, 'order'])->name('admin.hero-slides.order');
        Route::post('/homepage/hero-slides/{heroSlide}', [HeroSlideController::class, 'update'])->name('admin.hero-slides.update');
        Route::delete('/homepage/hero-slides/{heroSlide}', [HeroSlideController::class, 'destroy'])->name('admin.hero-slides.destroy');
        Route::patch('/homepage/categories/order', [HomeCategoryImageController::class, 'order'])->name('admin.home-categories.order');
        Route::post('/homepage/categories/batch', [HomeCategoryImageController::class, 'batch'])->name('admin.home-categories.batch');
        Route::patch('/homepage/categories', [HomeCategoryImageController::class, 'update'])->name('admin.home-categories.update');
        Route::post('/homepage/categories/{categoryKey}/image', [HomeCategoryImageController::class, 'upload'])->name('admin.home-categories.upload');
        Route::delete('/homepage/categories/{categoryKey}/image', [HomeCategoryImageController::class, 'destroy'])->name('admin.home-categories.destroy');

        Route::apiResource('catalog-categories', CatalogCategoryController::class)
            ->parameters(['catalog-categories' => 'catalogCategory'])
            ->names('admin.catalog-categories');
        Route::apiResource('products', ProductController::class)->names('admin.products');
        Route::patch('/products/{product}/images/order', [ProductController::class, 'imageOrder'])->name('admin.products.images.order');
    });
});

Route::get('/{path?}', function () {
    return view('welcome');
})->where('path', '.*');

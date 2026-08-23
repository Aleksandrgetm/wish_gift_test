<?php

use App\Http\Controllers\ContactMessageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/occasion', '/catalog');
Route::redirect('/occasion/{path}', '/catalog')->where('path', '.*');
Route::post('/contact', [ContactMessageController::class, 'store'])->name('contact.store');

Route::get('/{path?}', function () {
    return view('welcome');
})->where('path', '.*');

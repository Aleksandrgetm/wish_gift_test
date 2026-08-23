<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/occasion', '/catalog');
Route::redirect('/occasion/{path}', '/catalog')->where('path', '.*');

Route::get('/{path?}', function () {
    return view('welcome');
})->where('path', '.*');

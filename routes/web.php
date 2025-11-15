<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("login");



Route::get('/admin/toggle-language', function () {
    $current = session('locale', app()->getLocale());
    $newLocale = strtolower($current) === 'ar' ? 'en' : 'ar';
    session(['locale' => $newLocale]);
    App::setLocale($newLocale);

    return redirect()->back();
})->name('filament.toggle-language');

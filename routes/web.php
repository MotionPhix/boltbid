<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/google/redirect', function () {
  return Socialite::driver('google')->redirect();
})->name('auth.google');

Route::get(
  '/auth/google/callback',
  \App\Http\Controllers\Social\handleProvider::class,
);

Route::post(
  'auth/request-otp',
  [\App\Http\Controllers\Social\OtpController::class, 'requestOtp']
)->name('otp.request');

Route::get(
  'auth/s/otp',
  [\App\Http\Controllers\Social\OtpController::class, 'seekOtp']
)->name('otp.seek');

Route::post(
  'auth/verify-otp',
  [\App\Http\Controllers\Social\OtpController::class, 'verifyOtp']
)->name('otp.verify');

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {

  /*Route::get('/', function () {
    return Inertia::render('Dashboard');
  })->name('dashboard');*/

  Route::get(
    '/',
    [\App\Http\Controllers\TenderController::class, 'dashboard']
  )->name('dashboard');

  Route::prefix('tenders')->group(function () {
    Route::get(
      '/',
      [\App\Http\Controllers\TenderController::class, 'index']
    )->name('tenders.index');

    Route::get(
      '/create',
      [\App\Http\Controllers\TenderController::class, 'create']
    )->name('tenders.create');

    Route::post(
      '/',
      [\App\Http\Controllers\TenderController::class, 'store']
    )->name('tenders.store');

    Route::get(
      '/{tender}',
      [\App\Http\Controllers\TenderController::class, 'show']
    )->name('tenders.show');

    Route::post(
      '/{tender}/analyze',
      [\App\Http\Controllers\TenderController::class, 'analyze']
    )->name('tenders.analyze');
  });
});

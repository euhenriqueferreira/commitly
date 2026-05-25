<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::livewire('/register', 'pages::authentication.register')
            ->name('authentication.register');
        Route::livewire('/login', 'pages::authentication.login')
            ->name('authentication.login');
        Route::livewire('/recovery', 'pages::authentication.recovery')
            ->name('authentication.recovery');
    });

    Route::livewire('/verify', 'pages::authentication.otp-verification')
        ->middleware('otp-pending')
        ->name('authentication.otp-verification');

    Route::middleware('auth')->group(function () {
        Route::livewire('/success', 'pages::authentication.otp-success')
            ->name('authentication.otp-success');

        Route::livewire('/codigos-de-recuperacao', 'pages::authentication.recovery-codes')
            ->middleware('recovery-codes-available')
            ->name('authentication.recovery-codes');

        Route::livewire('/redefinicao', 'pages::authentication.email-reset')
            ->middleware('recovery-authorized')
            ->name('authentication.email-reset');
    });

});

Route::middleware('auth')->group(function () {
    Route::prefix('/habits')->group(function () {
        Route::livewire('/create', 'pages::habits.create')->name('habits.create');
        Route::livewire('/{habit}/show', 'pages::habits.show')->name('habits.show');
        Route::livewire('/{habit}/edit', 'pages::habits.edit')->name('habits.edit');
    });
});
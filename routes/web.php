<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::livewire('/cadastro', 'pages::authentication.register')
                ->name('authentication.register');
        Route::livewire('/login', 'pages::authentication.login')
                ->name('authentication.login');
        Route::livewire('/recuperacao', 'pages::authentication.recovery')
                ->name('authentication.recovery');
    });

    Route::livewire('/verificacao', 'pages::authentication.otp-verification')
            ->middleware('otp-pending')    
            ->name('authentication.otp-verification');

    Route::middleware('auth')->group(function () {
        Route::livewire('/sucesso', 'pages::authentication.otp-success')
                ->name('authentication.otp-success');

        Route::livewire('/codigos-de-recuperacao', 'pages::authentication.recovery-codes')
                ->middleware('recovery-codes-available')
                ->name('authentication.recovery-codes');

        Route::livewire('/redefinicao', 'pages::authentication.email-reset')
                ->middleware('recovery-authorized')
                ->name('authentication.email-reset');
    });

});

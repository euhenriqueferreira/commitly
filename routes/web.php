<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function () {
    Route::livewire('/cadastro', 'pages::authentication.register')
        ->name('authentication.register');
    Route::livewire('/login', 'pages::authentication.login')
        ->name('authentication.login');
    Route::livewire('/verificacao', 'pages::authentication.otp-verification')
        ->name('authentication.otp-verification');
    Route::livewire('/sucesso', 'pages::authentication.otp-success')
        ->name('authentication.otp-success');
    Route::livewire('/codigos-de-recuperacao', 'pages::authentication.recovery-codes')
        ->name('authentication.recovery-codes');
    Route::livewire('/recuperacao', 'pages::authentication.recovery')
        ->name('authentication.recovery');
    Route::livewire('/redefinicao', 'pages::authentication.email-reset')
        ->name('authentication.email-reset');
});

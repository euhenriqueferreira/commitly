<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->group(function(){
    Route::livewire('/cadastro', 'pages::authentication.register');
    Route::livewire('/login', 'pages::authentication.login');
    Route::livewire('/verificacao', 'pages::authentication.otp-verification');
    Route::livewire('/sucesso', 'pages::authentication.otp-success');
    Route::livewire('/codigos-de-recuperacao', 'pages::authentication.recovery-codes');
    Route::livewire('/recuperacao', 'pages::authentication.recovery');
    Route::livewire('/redefinicao', 'pages::authentication.email-reset');
});

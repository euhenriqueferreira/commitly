<?php


use Illuminate\Support\Facades\Route;

Route::get('/mail/otp', function () {
$path = public_path('assets/images/logotipo-commitly-3x.png');

$data = file_get_contents($path);

dd([
    'exists' => file_exists($path),
    'size' => strlen($data),
    'base64_preview' => substr(base64_encode($data), 0, 100),
]);
    return view('emails.authentication.otp-verification', [
        'code' => '123456',
        'expiresIn' => config('auth.otp.expires_in'),
    ]);
});

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

<?php

namespace App\Services;

use Resend\Laravel\Facades\Resend;

class EmailService
{
    public function sendOtp(string $email, string $code): void
    {
        Resend::emails()->send([
            'from' => 'Commitly <onboarding@resend.dev>',
            'to' => $email,
            'subject' => 'Seu código de verificação',
            'html' => view('emails.authentication.otp-verification', [
                'code' => $code,
                'expiresIn' => config('auth.otp.expires_in'),
            ])->render(),
        ]);
        // $html = view('emails.authentication.otp-verification', [
        //      'code' => $code,
        //     'expiresIn' => config('auth.otp.expires_in'),
        // ])->render();

        // dd($html);
    }
}
<?php

namespace App\Enum\Authentication\Otp;

enum OtpVerificationTypeEnum: string
{
    case LOGIN = 'login';
    case REGISTER = 'register';
    case EMAIL_CHANGE = 'email_change';
    case RECOVERY = 'recovery';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

namespace App\Services\Authentication;

use App\Models\OtpCode;
use App\Models\User;
use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function __construct(
        private EmailService $emailService
    ) {}

    public function generate(User $user, string $email, string $type): string
    {
        $this->invalidatePreviousOtps($email, $type);

        $code = $this->generateCode();

        OtpCode::create([
            'user_id' => $user->id,
            'email' => $email,
            'code_hash' => Hash::make($code),
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(5),
            'attempts' => 0,
            'max_attempts' => config('auth.otp.max_attempts'),
        ]);

        $this->emailService->sendOtp('support.commitly@gmail.com', $code);

        return $code;
    }

    public function validate(User $user, string $email, string $code, string $type): bool
    {
        $otp = OtpCode::query()->where('user_id', $user->id)
            ->where('email', $email)
            ->where('type', $type)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (! $otp) {
            return false;
        }

        if ($otp->expires_at->isPast()) {
            return false;
        }

        if ($otp->attempts >= 5) {
            return false;
        }

        $otp->increment('attempts');

        if (! Hash::check($code, $otp->code_hash)) {
            return false;
        }

        $otp->update([
            'consumed_at' => now(),
        ]);

        return true;
    }

    private function invalidatePreviousOtps(string $email, string $type): void
    {
        OtpCode::query()->where('email', $email)
            ->where('type', $type)
            ->whereNull('consumed_at')
            ->update([
                'consumed_at' => now(),
            ]);
    }

    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

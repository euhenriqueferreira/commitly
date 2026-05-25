<?php

namespace App\Services\Authentication;

use App\Enum\Authentication\Otp\OtpVerificationTypeEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    public function __construct(
        private RecoveryService $recoveryService,
        private OtpService $otpService,
    ) {}

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = $this->createUser($data);

            $recoveryCodes = $this->recoveryService->generateForUser($user);

            session()->put('recovery_codes', $recoveryCodes);

            $this->otpService->generate(
                user: $user,
                email: $user->email,
                type: OtpVerificationTypeEnum::REGISTER->value
            );

            return $user;
        });
    }

    private function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
        ]);
    }
}

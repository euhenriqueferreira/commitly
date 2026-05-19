<?php

namespace App\Services\Authentication;

use App\Models\RecoveryCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RecoveryService
{
    public function generateForUser(User $user): array
    {
        $codes = [];

        $quantity = config('auth.recovery.code_quantity', 8);

        for ($i = 0; $i < $quantity; $i++) {
            $plain = $this->generateFormattedCode();

            RecoveryCode::create([
                'user_id' => $user->id,
                'code_hash' => Hash::make($this->normalize($plain)),
            ]);

            $codes[] = $plain;
        }

        return $codes;
    }

    public function validate(User $user, string $code): bool
    {
        $backup = RecoveryCode::query()->where('user_id', $user->id)
            ->whereNull('used_at')
            ->get()
            ->first(fn ($item) => Hash::check($code, $item->code_hash));

        if (! $backup) {
            return false;
        }

        $backup->update([
            'used_at' => now(),
        ]);

        return true;
    }

    private function generateFormattedCode(): string
    {
        $raw = Str::upper(Str::random(8));

        return substr($raw, 0, 4).'-'.substr($raw, 4, 4);
    }

    private function normalize(string $code): string
    {
        return str_replace('-', '', $code);
    }
}

<?php

namespace App\Models;

use App\Enum\Authentication\Otp\OtpVerificationTypeEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'email', 'type', 'expires_at', 'attempts', 'max_attempts', 'consumed_at', 'code_hash'])]
#[Hidden([])]
class OtpCode extends Model
{
    protected function casts(): array
    {
        return [
            'type' => OtpVerificationTypeEnum::class,
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
        ];
    }

    /* --- Relations --- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

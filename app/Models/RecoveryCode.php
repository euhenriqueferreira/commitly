<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'user_at', 'code_hash'])]
#[Hidden([])]
class RecoveryCode extends Model
{
    protected function casts(): array
    {
        return [];
    }

    /* --- Relations --- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

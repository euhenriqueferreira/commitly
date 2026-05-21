<?php

namespace App\Models;

use App\Enum\Habits\HabitTypeEnum;
use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'type', 'current_streak', 'best_streak', 'paused_at'])]
#[Hidden([])]
class Habit extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'type' => HabitTypeEnum::class,
            'paused_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function versions(): HasMany
    {
        return $this->hasMany(HabitVersion::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(HabitCompletion::class);
    }

    public function currentVersion(): HasOne
    {
        return $this->hasOne(HabitVersion::class)->whereNull('valid_until');
    }
}

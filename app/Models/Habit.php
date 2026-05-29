<?php

namespace App\Models;

use App\Enum\Habits\HabitTypeEnum;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'name', 'type', 'current_version_id', 'current_streak', 'best_streak', 'paused_at'])]
#[Hidden([])]
class Habit extends Model
{
    use HasFactory;
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

    public function currentVersion(): BelongsTo
    {
        return $this->belongsTo(HabitVersion::class, 'current_version_id');
    }

    public function versionForDate(Carbon $date): ?HabitVersion
    {
        return $this->versions()
            ->whereDate('valid_from', '<=', $date->toDateString())
            ->where(function ($query) use ($date) {
                $query
                    ->whereNull('valid_until')
                    ->orWhereDate('valid_until', '>=', $date);
            })
            ->latest('valid_from')
            ->first();
    }

    public function isEnded(): bool
    {
        return $this->currentVersion->ends_at && $this->currentVersion->ends_at->isPast();
    }
}

<?php

namespace App\Models;

use App\Enum\Habits\WeekdayEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['habit_version_id', 'weekday'])]
#[Hidden([])]
class HabitVersionWeekday extends Model
{
    protected function casts(): array
    {
        return [
            'weekday' => WeekdayEnum::class,
        ];
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(HabitVersion::class);
    }
}

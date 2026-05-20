<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['habit_id', 'completion_date', 'completed_at'])]
#[Hidden([])]
class HabitCompletion extends Model
{
    protected function casts(): array
    {
        return [
            'completion_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function habit(): BelongsTo
    {
        return $this->belongsTo(Habit::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'icon', 'color'])]
#[Hidden([])]
class Category extends Model
{
    public function habits(): HasMany
    {
        return $this->hasMany(Habit::class);
    }
}

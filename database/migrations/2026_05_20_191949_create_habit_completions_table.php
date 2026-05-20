<?php

use App\Models\Habit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habit_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Habit::class)->constrained()->cascadeOnDelete();
            $table->date('completion_date');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->unique(['habit_id', 'completion_date']);
            $table->index(['habit_id', 'completion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_completions');
    }
};

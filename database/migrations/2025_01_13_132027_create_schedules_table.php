<?php

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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id('schedule_id');
            $table->foreignId('department_id')->constrained('departments', 'department_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('course_id')->constrained('courses','course_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('room_id')->constrained('rooms','room_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('start_time');
            $table->time('end_time');
            $table->json('weekdays');
            $table->json('sections');
            $table->foreignId('year_level_id')->constrained('year_levels', 'year_level_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

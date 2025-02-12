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
            $table->foreignId('schedule_draft_id')->constrained('schedule_drafts', 'schedule_draft_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('class_id')->nullable();
            $table->foreignId('course_no_id')->constrained('courses_no','course_no_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('room_id')->nullable()->constrained('rooms','room_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('combined_schedule_id')->nullable()->constrained('schedules', 'schedule_id')->onDelete('set null')->onUpdate('set null');
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

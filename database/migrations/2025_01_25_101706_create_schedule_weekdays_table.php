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
        Schema::create('schedule_weekdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules', 'schedule_id')->cascadeOnDelete();
            $table->string('day');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_weekdays');
    }
};

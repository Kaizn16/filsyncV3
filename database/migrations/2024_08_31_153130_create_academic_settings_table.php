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
        Schema::create('academic_settings', function (Blueprint $table) {
            $table->id('academic_setting_id');
            $table->string('academic_year');
            $table->enum('semester', ['1st Semester', '2nd Semester', 'Summer']);        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};

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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('semester');
            $table->unsignedBigInteger('year_level_id')->nullable();
            $table->unsignedBigInteger('course_no_id')->nullable();
            $table->string('academic_year');
            $table->boolean('is_deleted')->default(0);  
            $table->timestamps();

            $table->foreign('department_id')
                ->references('department_id')
                ->on('departments')
                ->OnDelete('set null')
                ->OnUpdate('cascade');
                
            $table->foreign('course_id')
                ->references('course_id')
                ->on('courses')
                ->OnDelete('set null')
                ->OnUpdate('cascade');

            $table->foreign('year_level_id')
                ->references('year_level_id')
                ->on('year_levels')
                ->OnDelete('set null')
                ->OnUpdate('cascade');

            $table->foreign('course_no_id')
                ->references('course_no_id')
                ->on('courses_no')
                ->OnDelete('cascade')
                ->OnUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

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
        Schema::create('courses_no', function (Blueprint $table) {
            $table->id('course_no_id');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('course_no');
            $table->string('descriptive_title');
            $table->integer('credits');
            $table->string('year_level');
            $table->string('semester');
            $table->timestamps();


            $table->foreign('department_id')
                ->references('department_id')
                ->on('departments')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->foreign('course_id')
                ->references('course_id')
                ->on('courses')
                ->onUpdate('cascade')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses_no');
    }
};

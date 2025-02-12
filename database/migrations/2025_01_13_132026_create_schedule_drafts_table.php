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
        Schema::create('schedule_drafts', function (Blueprint $table) {
            $table->id('schedule_draft_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('department_id')->constrained('departments', 'department_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('course_id')->constrained('courses', 'course_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('draft_name');
            $table->string('year_level');
            $table->foreignId('section_id')->constrained('sections', 'section_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('semester');
            $table->string('academic_year');
            $table->enum('status', ['draft', 'saved', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('remarks')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_drafts');
    }
};

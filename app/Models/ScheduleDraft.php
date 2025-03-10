<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDraft extends Model
{
    protected $table = 'schedule_drafts';
    protected $primaryKey = 'schedule_draft_id';

    protected $fillable = [
        'user_id',
        'department_id',
        'course_id',
        'draft_name',
        'year_level',
        'section_id',
        'semester',
        'academic_year',
        'status',
        'remarks',
        'is_deleted',
        'submitted_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    
}
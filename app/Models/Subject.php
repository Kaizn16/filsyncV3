<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';

    protected $fillable = [
        'department_id',
        'course_id',
        'semester',
        'year_level_id',
        'course_no_id',
        'academic_year',
        'is_deleted',
    ];

    public function deparment()
    {
        return $this->belongsTo(Department::class, 'deparment_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    public function year_level()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id');
    }

    public function course_no()
    {
        return $this->belongsTo(CoursesNo::class, 'course_no_id');
    }

}

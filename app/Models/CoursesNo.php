<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesNo extends Model
{
    use HasFactory;

    protected $table = 'courses_no';
    protected $primaryKey = 'course_no_id';

    protected $fillable = [
        'department_id',
        'course_id',
        'course_no',
        'descriptive_title',
        'credits',
    ];


    public function deparment()
    {
        return $this->belongsTo(Department::class, 'department_id');

    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

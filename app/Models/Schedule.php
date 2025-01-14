<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'department_id',
        'user_id',
        'course_id',
        'subject_id', 
        'room_id', 
        'start_time', 
        'end_time', 
        'weekdays',
        'sections',
        'year_level_id',
        'semester',
    ];

    protected $casts = [
        'weekdays' => 'array',
        'sections' => 'array'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class,'room_id');
    }

    public function year_level()
    {
        return $this->belongsTo(YearLevel::class,'year_level_id');
    }

}

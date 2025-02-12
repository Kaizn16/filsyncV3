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
        'schedule_draft_id',
        'class_id',
        'course_no_id',
        'user_id',
        'room_id', 
        'start_time', 
        'end_time',
        'combined_schedule_id'
    ];

    public function scheduleDraft()
    {
        return $this->belongsTo(ScheduleDraft::class, 'schedule_draft_id');
    }

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

    public function course_no()
    {
        return $this->belongsTo(CoursesNo::class,'course_no_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class,'room_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function weekdays()
    {
        return $this->hasMany(ScheduleWeekday::class, 'schedule_id');
    }
}

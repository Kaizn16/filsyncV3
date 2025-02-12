<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleWeekday extends Model
{
    protected $table = 'schedule_weekdays';
    protected $fillable = [
        'schedule_id',
        'day',
    ];

}

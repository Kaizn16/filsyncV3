<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Course extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'courses';
    protected $primaryKey = 'course_id';

    protected $fillable = [
        'department_id',
        'course_name',
        'abbreviation',
        'is_deleted',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}

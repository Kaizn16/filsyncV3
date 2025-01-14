<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSetting extends Model
{
    use HasFactory;

    protected $table = 'academic_settings';
    protected $primaryKey = 'academic_setting_id';

    protected $fillable = [
        'start_year',
        'end_year',
        'semester',
    ];
}

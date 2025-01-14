<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'departments';
    protected $primaryKey = 'department_id';

    protected $fillale = [
        'department_name',
        'abbreviation',
        'is_deleted'
    ];
}

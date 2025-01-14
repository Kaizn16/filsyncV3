<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Gender extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'genders';
    protected $primaryKey = 'gender_id';

    protected $fillable = [
        'gender',
    ];
}

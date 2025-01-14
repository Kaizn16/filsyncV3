<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_type',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

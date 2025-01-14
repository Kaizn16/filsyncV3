<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";

    protected $primaryKey = 'setting_id';

    protected $fillable = [
        'user_id',
        'email_notification',
        'country_region',
        'is_theme_dar',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

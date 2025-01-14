<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "rooms";
    protected $primaryKey = "room_id";
    protected $fillable = [
        "building_name",
        "room_name",
        "max_seat",
        "is_deleted",
    ] ;
}

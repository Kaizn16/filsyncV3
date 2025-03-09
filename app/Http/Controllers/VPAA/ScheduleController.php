<?php

namespace App\Http\Controllers\VPAA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('vpaa.schedules.schedules');
    }
}

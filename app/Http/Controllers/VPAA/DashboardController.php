<?php

namespace App\Http\Controllers\VPAA;

use App\Http\Controllers\Controller;
use App\Models\ScheduleDraft;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSubmittedScheduleDrafts = ScheduleDraft::whereIn('status', ['pending', 'approved', 'rejected'])->count();

        $totalPendingScheduleDrafts = ScheduleDraft::where('status', 'pending')->count();

        $totalApprovedScheduleDrafts = ScheduleDraft::where('status', 'approved')->count();

        $totalRejectedScheduleDrafts = ScheduleDraft::where('status', 'rejected')->count();

        $schedulesRecently = ScheduleDraft::with('user', 'department', 'course', 'section')
                            ->whereIn('status', ['pending', 'approved', 'rejected'])
                            ->orderBy('created_at', 'desc')
                            ->take(10)
                            ->get();


        return view('vpaa.dashboard',
            compact('totalSubmittedScheduleDrafts', 'totalPendingScheduleDrafts', 
            'totalApprovedScheduleDrafts', 'totalRejectedScheduleDrafts', 'schedulesRecently')
        );
    }
}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function index()
    {   
        $buildings = Room::distinct()->pluck('building_name');

        return view("superadmin.rooms.rooms", compact("buildings"));
    }

    public function store(Request $request)
    {
        $validate_data = $request->validate([
            "building_name" => "required",
            "room_name" => "required",
            "max_seat" => "required|numeric",
        ]);

        $existingRoomQuery = Room::where('building_name', $request->building_name)->where('room_name', $request->room_name);

        if ($request->has('is_deleted')) {
        $existingRoomQuery->where('is_deleted', $request->is_deleted);
        }

        $existingRoom = $existingRoomQuery->first() ?: null;

        if ($existingRoom) {
            return redirect()->back()->with([
                "message" => "Room already exists in that building!",
                'type' => 'warning',
            ])->withInput();

        }

        $room = Room::create($validate_data);

        if ($room) {
            return redirect()->route('superadmin.rooms')->with([
                "message" => "Room successfully created!",
                'type' => 'success',
            ]);
        }

        return redirect()->back()->with([
            "message" => "There was an error creating the room!",
            'type' => 'error',
        ])->withInput();

    }

    public function update(Request $request)
    {
        $validate_data = $request->validate([
            "building_name" => "required",
            "room_name" => "required",
            "max_seat" => "required|numeric",
        ]);
        
        $existingRoom = Room::where('building_name', $request->building_name)
                            ->where('room_name', $request->room_name)
                            ->where('room_id', $request->input('room_id'))
                            ->where('is_deleted', false)
                            ->first();
        
        if ($existingRoom) {
            if ($existingRoom->max_seat == $request->max_seat) {
                return redirect()->route('superadmin.rooms')->with([
                    "message" => "No changes detected, room already exists with the same data!",
                    'type' => 'info',
                ]);
            }
        
            $existingRoom->update($validate_data);
        
            if ($existingRoom) {
                return redirect()->route('superadmin.rooms')->with([
                    "message" => "Room successfully updated!",
                    'type' => 'success',
                ]);
            }
        }
        
        return redirect()->route('superadmin.rooms')->with([
            "message" => "There was an error updating the room!",
            'type' => 'error',
        ]);
        
    }

    public function delete(Request $request)
    {
        $room = Room::find($request->input('room_id'));
        $room->is_deleted = true;
        $room->save();
        
        return redirect()->route('superadmin.rooms')->with([
            "message" => "Room successfully deleted!",
            'type' => 'success',
        ]);
    }

    public function fetchRooms(Request $request)
    {
        $search = $request->input('search');
        $building = $request->input('building');
        $paginate = $request->input('paginate', 10);

        $query = Room::where('is_deleted', false);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('building_name', 'LIKE', '%' . $search . '%')
                ->orWhere('room_name', 'LIKE', '%' . $search . '%');
            });
        }

        if (!empty($building)) {
            $query->where('building_name', $building);
        }

        $rooms = $paginate === '' ? $query->limit(1000)->get() : $query->paginate($paginate);


        return response()->json($rooms);
    }

}

<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Role;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $positions = AppConstants::POSITIONS;

        return view("superadmin.users.users", compact("positions"));
    }

    public function fetchUsers(Request $request)
    {
        $search = $request->input('search');
        $position = $request->input('position');

        $query = User::with('department')->where('is_deleted', false)->whereHas('role', function ($q) {
            $q->where('role_type', '!=', 'superadmin');
        });

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }

        if (!empty($position)) {
            $query->where('position', $position);
        }

        $users = $query->paginate(10);

        return response()->json($users);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            "username" => "required|min:6",
            'password' => "required|min:8",
            "first_name" => "required",
            "middle_name" => "nullable",
            "last_name" => "required",
            "gender_id" => "required|exists:genders,gender_id",
            "position" => "required",
            "department" => "nullable|exists:departments,department_id",
            "contact_no" => "required",
            "email" => "required|email",
        ]);

        $existUsername = User::where("username", $validated["username"])->first();
        $existEmail = User::where("email", $validated["email"])->first();
        $existContactNo = User::where("contact_no", $validated["contact_no"])->first();

        if ($existUsername) {
            return redirect()->back()->with([
                'message' => 'Username is already taken!',
                'type' => 'error'
            ])->withInput();
        }
        
        if ($existEmail) {
            return redirect()->back()->with([
                'message' => 'Email is already taken!',
                'type' => 'error'
            ])->withInput();
        }

        if ($existContactNo) {
            return redirect()->back()->with([
                'message' => 'Contact number is already taken!',
                'type' => 'error'
            ])->withInput();
        }

        $role_id = $this->getRoleByPosition($validated['position']);

        $user = new User($validated);
        $user->role_id = $role_id;
        $user->department_id = $validated['department'] ?? null;
        $user->save();
        $user_setting = Setting::create(['user_id' => $user->user_id]);

        if($user->save() && $user_setting) {
            return redirect()->route('superadmin.users')->with([
                'message' => 'User created successfully!',
                'type' => 'success'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Failed to create new user!',
            'type' => 'error'
        ])->withInput();
    }  

    public function update(Request $request)
    {
        $user = User::find($request->input('user_id'));

        if (!$user) {
            return redirect()->route('superadmin.users')->with([
                'message' => 'User not found!',
                'type' => 'error'
            ]);
        }

        $validated = $request->validate([
            'username' => 'required|min:6',
            'password' => 'nullable|min:8',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'gender_id' => 'required|exists:genders,gender_id',
            'position' => 'required',
            'department' => 'nullable|exists:departments,department_id',
            'contact_no' => 'required',
            'email' => 'required|email',
        ]);

        $existUsername = User::where("username", $validated["username"])
            ->where("user_id", "!=", $user->user_id)
            ->first();
        $existEmail = User::where("email", $validated["email"])
            ->where("user_id", "!=", $user->user_id)
            ->first();
        $existContactNo = User::where("contact_no", $validated["contact_no"])
            ->where("user_id", "!=", $user->user_id)
            ->first();

        if ($existUsername) {
            return redirect()->back()->with([
                'message' => 'Username is already taken!',
                'type' => 'error'
            ])->withInput();
        }
        
        if ($existEmail) {
            return redirect()->back()->with([
                'message' => 'Email is already taken!',
                'type' => 'error'
            ])->withInput();
        }

        if ($existContactNo) {
            return redirect()->back()->with([
                'message' => 'Contact number is already taken!',
                'type' => 'error'
            ])->withInput();
        }

        $role_id = $this->getRoleByPosition($validated['position']);
        $user->role_id = $role_id;
        $user->department_id = $validated['department'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->fill($validated);
        unset($user->password);

        if($user->save($validated)) {
            return redirect()->route('superadmin.users')->with([
                'message' => 'User updated successfully!',
                'type' => 'success'
            ]);
        }

        return redirect()->route('superadmin.users')->with([
            'message' => 'Failed to updated user!',
            'type' => 'error'
        ]);
    }

    public function delete(Request $request) 
    {
        $user = User::find($request->input('user_id'));

        $user->is_deleted = true;

        if($user->save()) {
            return redirect()->route('superadmin.users')->with([
                'message' => 'User deleted successfully!',
                'type' => 'success'
            ]);
        }

        return redirect()->route('superadmin.users')->with([
            'message' => 'Unable to delete user!',
            'type' => 'error'
        ]);
    }

    public function getRoleByPosition($position)
    {
        $role_id = 0;
    
        switch ($position) {
            case 'DEAN':
                $role_id = 2; // ADMIN
                break;
            case 'SECRETARY':
                $role_id = 2; // ADMIN
                break;
            case 'TEACHER':
                $role_id = 3; // TEACHER
                break;
            case 'VPAA':
                $role_id = 4; // VPAA
                break;
            case 'REGISTRAR':
                $role_id = 5; // REGISTRAR
                break;
        }
    
        return $role_id;
    }
    
}

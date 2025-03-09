<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function index()
    {
        return view("auth.login");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if (!Auth::guard('web')->attempt(['username' => $validated['username'], 'password' => $validated['password']])) {
            return back()->withErrors(['login' => 'The provided credentials are incorrect.']);
        }

        $user = Auth::user();

        $this->updateLastLogin($user->user_id);
        $this->storeUserInSession($user);

        return $this->handleRoleRedirect($user);
    }

    /**
     * Update the last login timestamp in the users table
     *
     * @param int $userId
     * @return void
     */
    private function updateLastLogin($user_id)
    {
        DB::table('users')->where('user_id', $user_id)->update([
            'last_login' => Carbon::now(),
        ]);
    }

    /**
     * Store user data in session
     *
     * @param \App\Models\User $user
     * @return void
     */
    private function storeUserInSession($user)
    {
        session([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'role' => $user->role->role_type,
            'full_name' => $user->first_name . ($user->middle_name ? ' ' . $user->middle_name[0] . '. ' : ' ') . $user->last_name,
        ]);

        if ($user->role->role_type !== 'vpaa' && $user->role->role_type !== 'registrar' && $user->role->role_type !== 'superadmin') {
            session([
                'department' => optional($user->department)->department_name,
                'department_abbvr' => optional($user->department)->abbreviation,
            ]);
        }
    }

    /**
     * Handle user redirect based on their role
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleRoleRedirect($user)
    {
        switch ($user->role->role_type) {
            case 'superadmin':
                return redirect()->route('superadmin.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'vpaa':
                return redirect()->route('vpaa.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'registrar':
                return redirect()->route('registrar.dashboard');
            case 'hr':
                return redirect()->route('hr.dashboard');
            default:
                return redirect()->route('auth.logout');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

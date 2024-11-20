<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function userLoggedIn()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.user-login');
    }

    public function profileSettings()
    {
        return view('settings');
    }

    public function profileSettingsData()
    {
        // Fetch the currently authenticated user with their employee details (including address)
        $profileDetails = auth()->user()->load('employee');  // Eager load the 'employee' relationship

        // Return the authenticated user's profile details along with employee address
        return response()->json([
            'name' => $profileDetails->name,
            'email' => $profileDetails->email,
            'profile_picture' => $profileDetails->profile_picture,
            'birthday' => $profileDetails->employee ? $profileDetails->employee->birthday : null,
            'phone' => $profileDetails->employee ? $profileDetails->employee->phone : null,
            'address' => $profileDetails->employee ? $profileDetails->employee->address : null,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

}

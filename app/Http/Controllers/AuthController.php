<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


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

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        // Delete old profile picture if exists
        if ($user->profile_picture && Storage::exists('public/user_images/' . $user->profile_picture)) {
            Storage::delete('public/user_images/' . $user->profile_picture);
        }

        // Generate unique filename
        $filename = uniqid() . '.' . $request->file('profile_picture')->getClientOriginalExtension();

        // Store new profile picture
        $path = $request->file('profile_picture')->storeAs('public/user_images', $filename);

        // Update user's profile picture in database
        $user->profile_picture = $filename;
        $user->save();

        return response()->json([
            'success' => true,
            'profile_picture' => $filename,
            'message' => 'Profile picture updated successfully'
        ]);
    }
}

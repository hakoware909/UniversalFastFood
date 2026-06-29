<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'profile_picture_url' => $user->profile_picture ? 
                asset('storage/profile_pictures/' . $user->profile_picture) : 
                null
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user
        ]);
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        try {
            if ($request->hasFile('profile_picture')) {
                Log::info('Profile picture file detected');

                // Store the image in the profile_pictures directory
                $imagePath = $request->file('profile_picture')
                    ->store('profile_pictures', 'public');
                
                Log::info('Stored image path: ' . $imagePath);

                // Get just the filename from the path
                $filename = basename($imagePath);

                // Update user's profile picture
                $user->profile_picture = $filename;
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile picture updated successfully',
                    'path' => $filename,
                    'url' => asset('storage/profile_pictures/' . $filename)
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Profile picture upload error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload profile picture'
            ], 500);
        }
    }
} 
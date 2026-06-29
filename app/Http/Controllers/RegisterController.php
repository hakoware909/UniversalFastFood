<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Step 1: Validate the incoming request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'number' => 'required|digits_between:10,15',  // Assuming phone number length is between 10 to 15 digits
            'address' => 'required|string|max:255',
        ]);

        // Step 2: Attempt to create the user
        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'number' => $validated['number'],
                'address' => $validated['address'],
            ]);

            // Step 3: Return a success response that matches what frontend expects
            return response()->json([
                'status' => 'success',  // Changed to match what frontend checks for
                'message' => 'Registration successful! Please login.'
            ], 201);

        } catch (\Exception $e) {
            // Handle any potential exceptions
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    // Register
    public function register(Request $request)
    {
        // Validation should be added here (e.g., using $request->validate())
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'Email already registered'], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar'=>'users/default.png', // set deafult
            'role' => $request->role ?? 'job_seeker',
        ]);

        // Send signed email verification link
        $user->sendEmailVerificationNotification();

        // Optionally issue a token after verification (not immediately)
        // For now, return without token until email is verified
        return response()->json([
            'message' => 'Please verify your email',
            'user' => $user
        ], 201);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    // logout
    public function logout(){
        
        auth('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
}
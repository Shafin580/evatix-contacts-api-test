<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JWTAuth;
use GetResponses;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        // Check Input Validation
        $validatedData = validator($request->only(
            'name',
            'email',
            'password',
        ), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Return Error Response if validation fails
        if ($validatedData->fails()) {
            $jsonError = response()->json($validatedData->errors()->all(), 400);
            return GetResponses::validationError($jsonError->original);
        }

        // Create a User in database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activation_token' => Str::random(60) // For email or mobile otp activation
        ]);

        // Return Error Response if Create User Fails
        if (!$user) {
            return GetResponses::validationError("User couldn't created! Please check the server log!");
        }

        // Return Success Response containing token for user activation if User Creation Succeed
        return GetResponses::returnData(['activation_token' => $user->activation_token], 201);
    }

    // Activate the user account
    public function activate(Request $request)
    {
        //
    }

    // Login and return JWT token
    public function login(Request $request)
    {
        //
    }

    // Logout the user
    public function logout()
    {
        //
    }
}

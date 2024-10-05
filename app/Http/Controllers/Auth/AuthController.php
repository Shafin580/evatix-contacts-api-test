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
        //
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

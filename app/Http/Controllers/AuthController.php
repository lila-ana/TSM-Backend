<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dirape\Token\Token;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;


// use Ramsey\Uuid\Uuid;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        // $credentials = $request->validate(['email', 'password']);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['message' => 'Login successful', 'data' => $user]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Register 

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        // return $validatedData['name'];
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'api_token' => Str::random(60),
            'role' => $validatedData['role'], // Use the role specified in the registration form
            'active' => 1
        ]);

        // Assign a role to the user based on the 'role' field

        $roleName = $validatedData['role'];

        $user->assignRole($roleName);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}

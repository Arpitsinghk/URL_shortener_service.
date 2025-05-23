<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6'
    ]);


      if (User::where('email', $request->email)->exists()) {
        return response()->json([
            'message' => 'Email already registered',
            'success' => false
        ], 409); 
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
    ]);
    return response()->json([
        'message' => 'Registration successful',
        'success' => true,
        'token' => $user->createToken('token')->plainTextToken], 201);
}

public function login(Request $request) {
    $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([ 'message' => 'Login successfully',
        'success' => true,'token' => $request->user()->createToken('token')->plainTextToken]);
}


}

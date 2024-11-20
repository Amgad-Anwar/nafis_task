<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signUp(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $request->role ,
        ]);


        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success'=> true ,
            'token' => $token,
            'customer' => new CustomerResource($user),
        ] ) ;
    }

    public function signIn(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' =>  'invalid Phone Or Password',
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' =>  'User Found',
            'token' => $token,
            'customer' => new CustomerResource($user),
        ]);
    }
    public function signOut()
    {
        $customer =auth()->user();
        $customer->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}





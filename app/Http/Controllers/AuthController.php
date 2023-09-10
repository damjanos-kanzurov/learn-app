<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'terms_and_conditions' => 'accepted'
        ]);

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('learnapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        // return $this->success($data, 'Account successfully registered', 201);
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Wrong credentials'
            ], '401');
        }

        $token = $user->createToken('learnapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        Auth::login($user);

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        Auth::logout($user);

        return [
            'message' => 'Logged out'
        ];
    }
}

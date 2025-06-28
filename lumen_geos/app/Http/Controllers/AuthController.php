<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Tambahkan ini

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->input('username'))
            ->where('password', md5($request->input('password')))
            ->first();

        if ($user) {
            // Gunakan Str::random untuk membuat token acak
            $token = base64_encode(Str::random(40));
            return response()->json(['token' => $token, 'message' => 'Login successful']);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Menghapus token dari header atau sesi
        $request->user()->token()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

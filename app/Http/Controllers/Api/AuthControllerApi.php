<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthControllerApi extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('NIK', $request->nik)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'NIK atau password salah',
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'NIK' => $user->NIK,
                'nama' => $user->nama,
                'role' => $user->role,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16|unique:users,NIK',
            'nama' => 'required|string|max:100',
            'ttl' => 'required|date',
            'alamat' => 'required|string',
            'nohp' => 'required|string|max:15',
            'password' => 'required|min:6',
            'passwordconfirm' => 'required|same:password',
        ]);

        $user = User::create([
            'NIK' => $request->nik,
            'nama' => $request->nama,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'nohp' => $request->nohp,
            'password' => Hash::make($request->password),
            'role' => 'petani',
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'token' => $token,
        ], 201);
    }
}
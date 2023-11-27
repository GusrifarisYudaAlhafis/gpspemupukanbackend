<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|unique:users,username',
                'name' => 'required',
                'password' => 'required'
            ]);
            $registered = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'role' => 'unit',
                'password' => Hash::make($request->password)
            ]);
            $token = $registered->createToken('gps-pemupukan')->plainTextToken;
            return response()->json(['token' => $token]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}

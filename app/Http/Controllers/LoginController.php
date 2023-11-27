<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
            return Auth::attempt($request->only('username', 'password')) ? response()->json(['token' => Auth::user()->createToken('gps-pemupukan')->plainTextToken]) : response()->json(['error' => 'These credentials do not match our records.']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}

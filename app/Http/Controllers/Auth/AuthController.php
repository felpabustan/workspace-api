<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
          return response([
            'message' => 'Invalid credentials',
          ], Response::HTTP_UNAUTHORIZED);
        }

        $request->session()->regenerate();
    
        return response()->json(
            [
              'status'  => true,
              'message' => 'User Logged In Successfully',
              'user'    => Auth::user()->only(
                  [
                  'id',
                  'name',
                  'email',
                  ]
              ),
            ], 200
        );
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    
    public function user()
    {
        return Auth::user();
    }
}

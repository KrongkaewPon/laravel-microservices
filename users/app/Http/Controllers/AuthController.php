<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
                + [
                    'password' => \Hash::make($request->input('password')),
                    'is_admin' => $request->path() === 'api/admin/register' ? 1 : 0
                ]
        );

        return response($user, Response::HTTP_CREATED);
    }
    
    public function login(Request $request)
    {
        if (!\Auth::attempt($request->only('email', 'password'))) {
            return response([
                'error' => 'invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = \Auth::user();

        $jwt = $user->createToken('token', [$request->input('scope')])->plainTextToken;

        return compact('jwt');
    }
}

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
}

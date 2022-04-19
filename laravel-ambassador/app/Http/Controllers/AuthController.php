<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService =  $userService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->only('first_name', 'last_name', 'email', 'password')
            + ['is_admin' => $request->path() === 'api/admin/register' ? 1 : 0];

        $user = $this->userService->post("register", $data);

        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        return $_SERVER;
        return $request->headers->all();
        $scope = $request->path() === 'api/admin/login'  ? 'admin' : 'ambassador';

        $data = $request->only('email', 'password') + compact('scope');

        $response = $this->userService->post('login', $data);
        return $response;
        $cookie = cookie('jwt', $response['jwt'], 60 * 24); // 1 day

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return $this->userService->get('user');
    }

    public function logout()
    {
        $cookie = \Cookie::forget('jwt');

        $this->userService->post('logout', []);

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = $this->userService->put('user/info', $request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();

        $user = $this->userService->put('user/password', $request->only('password'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function scopeCan(Request $request, $scope)
    {
        if (!$request->user()->tokenCan($scope)) {
            abort(401, 'unauthorized');
        }

        return $next($request);
    }
}

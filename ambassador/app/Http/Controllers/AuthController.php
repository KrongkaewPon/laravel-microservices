<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Services\UserService;
use Illuminate\Http\Request;
use App\Models\Order;
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
            + ['is_admin' => 0];

        $user = $this->userService->post("register", $data);

        return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $data = $request->only('email', 'password') +  ['scope' => 'ambassador'];

        $response = $this->userService->post('login', $data);

        $cookie = cookie('jwt', $response['jwt'], 60 * 24); // 1 day

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        $user = $this->userService->get("user");
        $orders = Order::where('user_id', $user['id'])->get();
        $user['revenue'] = $orders->sum(fn (Order $order) => $order->total);

        return $user;
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
}

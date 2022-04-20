<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\Order;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService =  $userService;
    }

    public function index()
    {
        return OrderResource::collection(Order::with('orderItems')->get());
    }
}

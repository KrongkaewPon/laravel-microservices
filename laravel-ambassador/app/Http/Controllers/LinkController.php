<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\Link;
use App\Http\Resources\LinkResource;

class LinkController extends Controller
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService =  $userService;
    }

    public function index($id)
    {
        $links = Link::with('orders')->where('user_id', $id)->get();

        return LinkResource::collection($links);
    }
}

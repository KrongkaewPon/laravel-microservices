<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Http\Resources\LinkResource;

class LinkController extends Controller
{
    public function index($id)
    {
        $links = Link::with('orders')->where('user_id', $id)->get();

        return LinkResource::collection($links);
    }
}

<?php

namespace App\Services;

use App\Services\ApiService;

class UserService extends ApiService
{
    protected string $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_MS', 'http://users_ms:8000') . '/api';
    }

    public function post($path, $data)
    {
        return \Http::post("{$this->endpoint}/{$path}", $data)->json();
    }
}

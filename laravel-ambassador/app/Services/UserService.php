<?php

namespace App\Services;

use App\Services\ApiService;

class UserService extends ApiService
{
    protected string $endpoint;

    public function __construct()
    {
        $this->endpoint = 'docker.for.mac.localhost:8001/api';
    }

    public function post($path, $data)
    {
        return \Http::post("{$this->endpoint}/{$path}", $data)->json();
    }
}

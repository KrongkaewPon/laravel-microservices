<?php

namespace App\Services;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Services\ApiService;

class UserService extends ApiService
{
    protected string $endpoint;

    public function __construct()
    {
        $this->endpoint = env('USERS_MS', 'http://users_ms:8000') . '/api';
    }
}

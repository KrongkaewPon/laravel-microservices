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

    public function request($method, $path, $data = [])
    {
        $response = \Http::acceptJson()
        ->withHeaders([
            'Authorization' => 'Bearer ' . request()->cookie('jwt')
        ])
        ->$method("{$this->endpoint}/{$path}", $data);

        // if ($response->ok()) {
        if ($response->successful()) {
            return $response->json();
        }

        throw new HttpException($response->status(), $response->body());
    }

    public function post($path, $data)
    {
        return $this->request('post', $path, $data);
    }

    public function get($path)
    {
        return $this->request('get', $path);
    }

    public function put($path, $data)
    {
        return $this->request('put', $path, $data);
    }

    public function delete($path)
    {
        return $this->request('put', $path);
    }
}

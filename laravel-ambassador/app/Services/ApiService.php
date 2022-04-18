<?php

namespace App\Services;

abstract class ApiService
{
    protected string $endpoint;

    public function post($path, $data)
    {
        return \Http::post("{$this->endpoint}/{$path}", $data)->json();
    }
}

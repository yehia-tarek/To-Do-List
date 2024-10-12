<?php

namespace App\Services\User;

interface IUserService
{
    public function create($request);
    public function login($request);
}

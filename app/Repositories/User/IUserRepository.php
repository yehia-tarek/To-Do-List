<?php

namespace App\Repositories\User;

interface IUserRepository
{
    public function create(array $data);

    public function getByEmail(string $email);
}

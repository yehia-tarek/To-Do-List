<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\IUserRepository;

class UserRepository implements IUserRepository
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function getByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
}

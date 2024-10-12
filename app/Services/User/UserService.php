<?php

namespace App\Services\User;

use App\Traits\Api\ResponseTrait;
use Illuminate\Support\Str;
use App\Services\User\IUserService;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\IUserRepository;
use Illuminate\Validation\ValidationException;


class UserService implements IUserService
{
    use ResponseTrait;
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create($request)
    {
        $data = [
            'username' => $request['username'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => $request['password'],
            'remember_token' => Str::random(10),
        ];

        return $this->userRepository->create($data);
    }

    public function login($request)
    {
        $user = $this->userRepository->getByEmail($request['email']);

        if (!$user || !Hash::check($request['password'], $user->password)) {
            return $this->errorResponse('Invalid credentials', [], 401);
        }

        $token = $user->createToken($request->ip())->plainTextToken;

        return $this->successResponse(['token' => $token], 'User logged in successfully');
    }
}

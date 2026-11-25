<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return  $this->userRepository->getUsersList();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails(User  $user)
    {

        return  $this->userRepository->getUserDetails($user);
    }

    // addNewUser Funtion To Add new User
    public function addNewUser(array $user_request)
    {
        return $this->userRepository->addNewUser($this->prepareRequestInfo($user_request));
    }

    public function updateUser(array $user_request, User $user): User
    {
        $attributes = $this->prepareRequestInfo($user_request);

        return DB::transaction(function () use ($user, $attributes): User {
            $user = $this->userRepository->lockById($user);
            $user = $this->userRepository->updateUser($user, $attributes);

            if ($user->wasChanged(['password', 'status', 'user_type'])) {
                $this->userRepository->revokeTokens($user);
            }

            return $user;
        });
    }

    public function deleteUser(User $user): User
    {
        return DB::transaction(function () use ($user): User {
            $user = $this->userRepository->lockById($user);
            $this->userRepository->revokeTokens($user);

            return $this->userRepository->deleteUser($user);
        });
    }

    public function prepareRequestInfo(array $request_info)
    {
        $request_data = [
            'full_name' => $request_info['full_name'] ?? null,
            'user_name' => $request_info['user_name'] ?? null,
            'phone_number' => $request_info['phone_number'] ?? null,
            'email_address' => $request_info['email_address'] ?? null,
            'password' => isset($request_info['password'])
                    ? Hash::make($request_info['password'])
                    : null,
            'status' => $request_info['status'] ?? null,
            'user_type' => $request_info['user_type'] ?? null,
        ];

        if (isset($request_info['created_by'])) {
            $request_data['created_by'] = $request_info['created_by'];
        }

        if (isset($request_info['updated_by'])) {
            $request_data['updated_by'] = $request_info['updated_by'];
        }

        return $request_data;
    }

}


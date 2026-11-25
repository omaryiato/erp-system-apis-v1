<?php

namespace App\Repositories;

use App\Models\User;



class UserRepository
{

    public function findForLogin(string $login): ?User
    {
        $matches = User::query()
            ->where(fn ($query) => $query->where('user_name', $login)->orWhere('phone_number', $login))
            ->orderBy('id')
            ->limit(2)
            ->lockForUpdate()
            ->get();

        return $matches->count() === 1 ? $matches->first() : null;
    }

    public function lockById(User $user): User
    {
        return User::query()->lockForUpdate()->findOrFail($user->getKey());
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function issueToken(User $user): \Laravel\Sanctum\NewAccessToken
    {
        return $user->createToken('auth_token', ['*'], now()->addHours(2));
    }

    public function revokeCurrentToken(User $user): void
    {
        $token = $user->currentAccessToken();

        if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) {
            $token->delete();
        }
    }

    // getUsersList Funtion To Get Users List
    public function getUsersList()
    {
        return User::all();
    }

    // getUserDetails Funtion To Get User Details
    public function getUserDetails(User $user)
    {
        return $user;
    }

    // addNewUser Funtion To Add new User
    public function addNewUser(array $user_details)
    {
        return User::create($user_details);
    }

    // updateUser Funtion To Update User info
    public function updateUser(User $user, array $user_request)
    {
        $user->update($user_request);
        return $user;
    }

    // deleteUser Funtion To Delete User
    public function deleteUser(User $user)
    {
        $user->delete();
        return $user;
    }
}


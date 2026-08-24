<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Helpers\ResponseHelper;
use App\Http\Requests\User\AddUser;
use App\Http\Requests\User\UpdateUser;
use App\Models\User;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Funtion to Get Users List
    public function index()
    {
        $users_list = $this->userService->getUsersList();
        return ResponseHelper::success(
                UserResource::collection($users_list),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                200);
    }

    //  Funtion to Get User Details
    public function show(User $user)
    {

        $user_details =  $this->userService->getUserDetails($user);

        if (!$user_details) {
            return ResponseHelper::error(
                $user_details,
                [
                    'en' => trans('validation.data_not_found', [], 'en'),
                    'ar' => trans('validation.data_not_found', [], 'ar'),
                ],
                404);
        }

        return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                200);

    }

    //  Funtion To Add New User
    public function store(AddUser $request)
    {
        try{

            $user_details = $this->userService->addNewUser($request->validated());
            return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                201);

        } catch(\Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }

    }

    //  Funtion To Update User
    public function update(UpdateUser $request, User $user)
    {
        try {

            $user_details =  $this->userService->updateUser($request->validated(), $user);

            if (!$user_details) {
                return ResponseHelper::error(
                    new UserResource($user_details),
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_updated', [], 'en'),
                    'ar' => trans('validation.data_updated', [], 'ar'),
                ],
                201);

        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    // deleteUser Funtion To Delete User
    public function destroy(Request $request, User $user)
    {
        try {

            $user_details = $this->userService->deleteUser($request->all(), $user);

            if (!$user_details) {
                return ResponseHelper::error(
                    $user_details,
                    [
                        'en' => trans('validation.data_not_found', [], 'en'),
                        'ar' => trans('validation.data_not_found', [], 'ar'),
                    ],
                    404);
            }

            return ResponseHelper::success(
                    null,
                    [
                        'en' => trans('validation.data_deleted', [], 'en'),
                        'ar' => trans('validation.data_deleted', [], 'ar'),
                    ],
                    200);
        } catch (\Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

}

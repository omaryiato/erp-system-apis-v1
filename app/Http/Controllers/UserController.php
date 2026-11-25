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
use Symfony\Component\HttpFoundation\Response;

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
                Response::HTTP_OK);
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
                Response::HTTP_NOT_FOUND);
        }

        return ResponseHelper::success(
                new UserResource($user_details),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                Response::HTTP_OK);

    }

    public function store(AddUser $request)
    {
        $user = $this->userService->addNewUser($request->validated(), $request->user()->id);

        return ResponseHelper::success(
            new UserResource($user),
            [
                'en' => trans('validation.data_added', [], 'en'),
                'ar' => trans('validation.data_added', [], 'ar'),
            ],
            Response::HTTP_CREATED
        );
    }

    public function update(UpdateUser $request, User $user)
    {
        $user = $this->userService->updateUser($request->validated(), $user, $request->user()->id);

        return ResponseHelper::success(
            new UserResource($user),
            [
                'en' => trans('validation.data_updated', [], 'en'),
                'ar' => trans('validation.data_updated', [], 'ar'),
            ],
            Response::HTTP_CREATED
        );
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return ResponseHelper::success(
            null,
            [
                'en' => trans('validation.data_deleted', [], 'en'),
                'ar' => trans('validation.data_deleted', [], 'ar'),
            ],
            Response::HTTP_OK
        );
    }
}

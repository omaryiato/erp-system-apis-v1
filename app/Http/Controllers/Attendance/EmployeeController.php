<?php

namespace App\Http\Controllers\Attendance;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\EmployeeRequest;
use App\Http\Resources\Attendance\EmployeeResource;
use App\Models\Attendance\Employee;
use App\Services\Attendance\EmployeeService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                EmployeeResource::collection($this->service->all()),
                [
                    'en' => trans('validation.get_employee_list', [], 'en'),
                    'ar' => trans('validation.get_employee_list', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function store(EmployeeRequest $request)
    {

        try {
            $employee = $this->service->create(
                    $request->validated()
                );
            return ResponseHelper::success(
                    new EmployeeResource($employee),
                    [
                        'en' => trans('validation.add_new_employee', [], 'en'),
                        'ar' => trans('validation.add_new_employee', [], 'ar'),
                    ],
                    Response::HTTP_CREATED
                );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function show(Employee $employee)
    {
        return ResponseHelper::success(
                new EmployeeResource($employee),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        EmployeeRequest $request,
        Employee $employee
    ) {


        try {
            return ResponseHelper::success(
                new EmployeeResource($this->service->update(
                    $employee,
                    $request->validated()
                )),
                [
                    'en' => trans('validation.update_employee', [], 'en'),
                    'ar' => trans('validation.update_employee', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }

    }

    public function destroy(Employee $employee)
    {
        return ResponseHelper::success(
                $this->service->delete($employee),
                [
                    'en' => trans('validation.delete_employee', [], 'en'),
                    'ar' => trans('validation.delete_employee', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}

<?php

namespace App\Http\Controllers\HR;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Employee\AddNewEmployee;
use App\Http\Requests\HR\Employee\DeleteEmployee;
use App\Services\HR\EmployeeService;
use App\Http\Requests\HR\Employee\UpdateEmployee;
use App\Http\Resources\EmployeeResource;
use App\Models\HR\Employee;
use Exception;
use Symfony\Component\HttpFoundation\Response;




class EmployeeController extends Controller
{


    public function __construct(
        protected EmployeeService $service
    )
    {}



    public function index()
    {
        return ResponseHelper::success(
                EmployeeResource::collection($this->service->getAllEmployee()),
                [
                    'en' => trans('validation.get_employee_list', [], 'en'),
                    'ar' => trans('validation.get_employee_list', [], 'ar'),
                ],
                Response::HTTP_OK
            );

    }

    public function store(AddNewEmployee $request)
    {
        try {
            $employee_details = $this->service->addNewEmployee(
                $request->validated()
            );

            return ResponseHelper::success(
                    new EmployeeResource($employee_details),
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
                new EmployeeResource($this->service->getEmployeeDetails($employee)),
                [
                    'en' => trans('validation.get_employee_details', [], 'en'),
                    'ar' => trans('validation.get_employee_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }


    public function update(Employee $employee, UpdateEmployee $request)
    {

        try {
            return ResponseHelper::success(
                new EmployeeResource($this->service->updateEmployeeInfo(
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
                $this->service->deleteEmployee($employee),
                [
                    'en' => trans('validation.delete_employee', [], 'en'),
                    'ar' => trans('validation.delete_employee', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );

    }



}

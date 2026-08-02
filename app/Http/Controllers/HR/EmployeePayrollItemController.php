<?php

namespace App\Http\Controllers\HR;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeePayrollItem\AddNewEmployeePayrollItem;
use App\Http\Requests\HR\EmployeePayrollItem\DeleteEmployeePayroll;
use App\Services\HR\EmployeePayrollItemService;
use App\Http\Requests\HR\EmployeePayrollItem\UpdateEmployeePayrollItem;
use App\Http\Resources\EmployeePayrollItemResource;
use App\Models\HR\Employee;
use App\Models\HR\EmployeePayrollItem;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class EmployeePayrollItemController extends Controller
{


    public function __construct(
        protected EmployeePayrollItemService $service
    )
    {}





    public function index()
    {
        return ResponseHelper::success(
                    EmployeePayrollItemResource::collection($this->service->getAllEmployeePayrolls()),
                    [
                        'en' => trans('validation.get_employee_payroll_list', [], 'en'),
                        'ar' => trans('validation.get_employee_payroll_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }





    public function store(AddNewEmployeePayrollItem $request)
    {

        try {

            $employee_payroll_details = $this->service->addNewEmployeePayroll($request->validated());

            return ResponseHelper::success(
                    new EmployeePayrollItemResource($employee_payroll_details),
                    [
                        'en' => trans('validation.add_new_employee_payroll', [], 'en'),
                        'ar' => trans('validation.add_new_employee_payroll', [], 'ar'),
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





    public function show(EmployeePayrollItem $employeePayroll)
    {
        return ResponseHelper::success(
                    new EmployeePayrollItemResource($this->service->getEmployeePayrollDetails($employeePayroll)),
                    [
                        'en' => trans('validation.get_employee_payroll_list', [], 'en'),
                        'ar' => trans('validation.get_employee_payroll_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );

    }





    public function update( EmployeePayrollItem $employeePayroll, UpdateEmployeePayrollItem $request)
    {
        try {
            return ResponseHelper::success(
                new EmployeePayrollItemResource(
                    $this->service->updateEmployeePayroll(
                        $employeePayroll,
                        $request->validated()
                    )
                ),
                [
                    'en' => trans('validation.update_employee_payroll', [], 'en'),
                    'ar' => trans('validation.update_employee_payroll', [], 'ar'),
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





    public function destroy(DeleteEmployeePayroll $request)
    {
        return ResponseHelper::success(
                $this->service->deleteEmployeePayroll($request->validated()),
                [
                    'en' => trans('validation.delete_employee_payroll', [], 'en'),
                    'ar' => trans('validation.delete_employee_payroll', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }





    public function employeePayroll(Employee $employee)
    {
        return ResponseHelper::success(
                    EmployeePayrollItemResource::collection($this->service->getEmployeePayrolls($employee)),
                    [
                        'en' => trans('validation.get_employee_payroll_list', [], 'en'),
                        'ar' => trans('validation.get_employee_payroll_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );

    }





    // public function activeItems($employeeId)
    // {

    //     return EmployeePayrollItemResource::collection(

    //     $this->service->activeItems(

    //         $employeeId,

    //         request('date',now()->format('Y-m-d'))

    //     )

    //     );

    // }


}

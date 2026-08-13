<?php

namespace App\Http\Controllers\HR;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Attendance\AddNewAttendance;
use App\Http\Requests\HR\Attendance\DeleteAttendance;
use App\Services\HR\AttendanceService;
use App\Http\Requests\HR\Attendance\UpdateAttendance;
use App\Http\Resources\AttendanceResource;
use App\Models\HR\Attendance;
use App\Models\HR\Employee;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{


    public function __construct(
        protected AttendanceService $service
    )
    {}




    public function index()
    {
        return ResponseHelper::success(
                    AttendanceResource::collection($this->service->getAllAttendance()),
                    [
                        'en' => trans('validation.get_attendance_list', [], 'en'),
                        'ar' => trans('validation.get_attendance_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );

    }




    public function store(AddNewAttendance $request)
    {

        try {

            $attendance_details = $this->service->addNewAttendance($request->validated());

            return ResponseHelper::success(
                    new AttendanceResource($attendance_details),
                    [
                        'en' => trans('validation.add_new_attendacne', [], 'en'),
                        'ar' => trans('validation.add_new_attendance', [], 'ar'),
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




    public function show(Attendance $attendance)
    {
        return ResponseHelper::success(
                new AttendanceResource($this->service->getAttendanceDetails($attendance)),
                [
                    'en' => trans('validation.get_attendance_details', [], 'en'),
                    'ar' => trans('validation.get_attendance_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );

    }


    public function update(Attendance $attendance, UpdateAttendance $request)
    {

        try {
            return ResponseHelper::success(
                new AttendanceResource(
                    $this->service->updateAttendance(
                        $attendance,
                        $request->validated()
                    )
                ),
                [
                    'en' => trans('validation.update_attendance', [], 'en'),
                    'ar' => trans('validation.update_attendance', [], 'ar'),
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





    public function destroy(Attendance $attendance)
    {
        return ResponseHelper::success(
                $this->service->deleteAttendance($attendance),
                [
                    'en' => trans('validation.delete_attendance', [], 'en'),
                    'ar' => trans('validation.delete_attendance', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }



    public function employeeAttendance(Employee $employee)
    {
        $employee_attendance = $this->service->employeeAttendance($employee, request('month'), request('year'));

        return ResponseHelper::success(
                AttendanceResource::collection($employee_attendance),
                [
                    'en' => trans('validation.get_employee_attendance', [], 'en'),
                    'ar' => trans('validation.get_employee_attendance', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }



}

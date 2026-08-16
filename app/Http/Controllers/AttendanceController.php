<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceRequest;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\AttendanceService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $service
    ) {}


    public function index()
    {
        return ResponseHelper::success(
                    AttendanceResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_attendance_list', [], 'en'),
                        'ar' => trans('validation.get_attendance_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );

    }

    public function store(
        AttendanceRequest $request
    ) {

        try {

            $attendance = $this->service->create(
            $request->validated());

            return ResponseHelper::success(
                    new AttendanceResource($attendance),
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
                new AttendanceResource($attendance),
                [
                    'en' => trans('validation.get_attendance_details', [], 'en'),
                    'ar' => trans('validation.get_attendance_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        AttendanceRequest $request,
        Attendance $attendance
    ) {

        try {
            return ResponseHelper::success(
                new AttendanceResource(
                    $this->service->update(
                        $attendance,
                        $request->validated()
                    )),
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

    public function employeeAttendance(
        Request $request,
        Employee $employee
    ) {

        $attendance = $this->service
            ->employeeAttendance(
                $employee,
                $request->from,
                $request->to
            );

        return ResponseHelper::success(
                AttendanceResource::collection(
                        $attendance
                    ),
                [
                    'en' => trans('validation.get_employee_attendance', [], 'en'),
                    'ar' => trans('validation.get_employee_attendance', [], 'ar'),
                ],
                Response::HTTP_OK
            );

    }
}

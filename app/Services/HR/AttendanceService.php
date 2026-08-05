<?php

namespace App\Services\HR;

use App\Models\HR\Attendance;
use App\Models\HR\Employee;
use App\Repositories\HR\AttendanceRepository;
use Carbon\Carbon;

class AttendanceService
{


    public function __construct(
        protected AttendanceRepository $repository
    )
    {}



    public function getAllAttendance()
    {
        return $this->repository->getAllAttendance();
    }



    public function getAttendanceDetails(Attendance $attendance)
    {
        return $this->repository->getAttendanceDetails($attendance);
    }



    public function addNewAttendance(array $attendance_request)
    {

        // if(isset($attendance_request['check_in'])){

        //     $check_in =Carbon::parse($attendance_request['check_in']);


        //     $start = $check_in->copy()->setTime(8,0);


        //     if($check_in->greaterThan($start))
        //     {

        //         $attendance_request['late_minutes'] = $start->diffInMinutes($check_in);

        //     }

        // }

        return $this->repository->AddNewAttendance($this->prepareAttendanceInfo($attendance_request));

    }



    public function updateAttendance(Attendance $attendance,array $attendance_request)
    {
        return $this->repository->updateAttendance($attendance, $this->prepareAttendanceInfo($attendance_request));
    }



    public function deleteAttendance(Attendance $attendance)
    {
        // $attendance = $this->repository->getAttendanceDetails($attendance_request['id']);
        return $this->repository->deleteAttendance($attendance);
    }


    public function employeeAttendance(Employee $employee,  $from, $to)
    {
        return $this->repository->employeeAttendance($employee, $from, $to);
    }

    public function prepareAttendanceInfo(array $attendance_request)
    {
        $attendance_data =  [
            'employee_id' => $attendance_request['employee_id'] ?? null,
            'work_date' => $attendance_request['work_date'] ?? null,
            'check_in' => $attendance_request['check_in'] ?? now(),
            'check_out' => $attendance_request['check_out'] ?? now(),
            'source' => $attendance_request['source'] ?? null,
            'status' => $attendance_request['status'] ?? null,
            'late_minutes' => $attendance_request['late_minutes'] ?? null,
            'notes' => $attendance_request['notes'] ?? 'active',
            'created_at' => now(),
        ];

        if (isset($attendance_request['created_by'])) {
            $attendance_data['created_by'] = $attendance_request['created_by'];
        }

        return $attendance_data;
    }


}

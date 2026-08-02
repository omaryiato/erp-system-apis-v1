<?php

namespace App\Repositories\HR;


use App\Models\HR\Attendance;
use App\Models\HR\Employee;

class AttendanceRepository
{

    public function getAllAttendance()
    {
        return Attendance::with('employee')
                ->latest()
                ->get();
    }




    public function getAttendanceDetails(Attendance $attendance)
    {
        return $attendance->load('employee');
    }




    public function AddNewAttendance(array $attendance_request)
    {
        return Attendance::create($attendance_request);
    }




    public function updateAttendance(Attendance $attendance,array $attendance_request)
    {
        $attendance->update($attendance_request);
        return $attendance;
    }




    public function deleteAttendance(Attendance $attendance)
    {
        $attendance->delete();
        return $attendance;
    }





    public function employeeAttendance(Employee $employee, $from=null, $to=null)
    {

        $query = Attendance::where('employee_id', $employee->id);


        if($from)
        {
            $query->whereDate(
                'work_date',
                '>=',
                $from
            );
        }



        if($to)
        {
            $query->whereDate(
                'work_date',
                '<=',
                $to
            );
        }



        return $query
            ->orderBy('work_date')
            ->get();

    }



}

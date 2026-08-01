<?php

namespace App\Repositories\HR;


use App\Models\HR\Attendance;

class AttendanceRepository
{

    public function getAllAttendance()
    {

        return Attendance::with('employee')
                ->latest()
                ->get();

    }




    public function getAttendanceDetails($id)
    {

        return Attendance::with('employee')
                ->findOrFail($id);

    }




    public function AddNewAttendance(array $data)
    {

        return Attendance::create($data);

    }




    public function updateAttendanceInfo($id,array $data)
    {

        $attendance =
            Attendance::findOrFail($id);


        $attendance->update($data);


        return $attendance;

    }




    public function deleteAttendance($id)
    {

        return Attendance::findOrFail($id)
                         ->delete();

    }





    public function employeeAttendance(
        $employeeId,
        $from=null,
        $to=null
    )
    {

        $query = Attendance::where(
            'employee_id',
            $employeeId
        );


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

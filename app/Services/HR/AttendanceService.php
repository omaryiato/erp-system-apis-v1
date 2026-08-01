<?php

namespace App\Services\HR;


use App\Repositories\HR\AttendanceRepository;



class AttendanceService
{


    public function __construct(
        protected AttendanceRepository $repository
    )
    {}



    public function getAll()
    {

        return $this->repository->getAllAttendance();

    }




    public function getById($id)
    {

        return $this->repository->getAttendanceDetails($id);

    }





    public function create(array $data)
    {


        /*
        Calculate late minutes
        Example:
        Shift starts 08:00
        */


        if(
            isset($data['check_in'])
        ){

            $checkIn =
                \Carbon\Carbon::parse(
                    $data['check_in']
                );


            $start =
                $checkIn->copy()
                ->setTime(8,0);


            if($checkIn->greaterThan($start))
            {

                $data['late_minutes'] =
                    $start->diffInMinutes($checkIn);

            }

        }



        return $this->repository
                    ->AddNewAttendance($data);

    }






    public function update($id,array $data)
    {

        return $this->repository
                    ->updateAttendanceInfo($id,$data);

    }






    public function delete($id)
    {

        return $this->repository
                    ->deleteAttendance($id);

    }





    public function employeeAttendance(
        $employeeId,
        $from,
        $to
    )
    {

        return $this->repository
                    ->employeeAttendance(
                        $employeeId,
                        $from,
                        $to
                    );

    }


}

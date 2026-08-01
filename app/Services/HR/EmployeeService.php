<?php

namespace App\Services\HR;


use App\Repositories\HR\EmployeeRepository;



class EmployeeService
{


    public function __construct(
        protected EmployeeRepository $repository
    )
    {}



    public function getAllEmployee()
    {
        return $this->repository->getAllEmployee();
    }



    public function getEmployeeDetails($id)
    {
        return $this->repository->getEmployeeDetails($id);
    }




    public function addNewEmployee(array $data)
    {

        return $this->repository->addNewEmployee($data);

    }




    public function updateEmployeeInfo($id,array $data)
    {

        return $this->repository->updateEmployeeInfo(
            $id,
            $data
        );

    }




    public function deleteEmployee($id)
    {

        return $this->repository->deleteEmployee($id);

    }



}

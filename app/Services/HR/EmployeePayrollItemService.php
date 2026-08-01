<?php

namespace App\Services\HR;


use App\Repositories\HR\EmployeePayrollItemRepository;



class EmployeePayrollItemService
{


public function __construct(
    protected EmployeePayrollItemRepository $repository
)
{}




public function getAll()
{

return $this->repository->all();

}




public function getById($id)
{

return $this->repository->find($id);

}





public function create(array $data)
{


return $this->repository->create($data);


}





public function update($id,array $data)
{

return $this->repository->update(
    $id,
    $data
);

}





public function delete($id)
{

return $this->repository->delete($id);

}





public function employeeItems($employeeId)
{

return $this->repository
            ->employeeItems(
                $employeeId
            );

}





public function activeItems(
    $employeeId,
    $date
)
{

return $this->repository
            ->activeItems(
                $employeeId,
                $date
            );

}



}

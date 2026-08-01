<?php

namespace App\Services\HR;


use App\Repositories\HR\PayrollItemTypeRepository;



class PayrollItemTypeService
{


public function __construct(
    protected PayrollItemTypeRepository $repository
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


}

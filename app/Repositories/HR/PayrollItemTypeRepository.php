<?php

namespace App\Repositories\HR;


use App\Models\HR\PayrollItemType;



class PayrollItemTypeRepository{


public function all()
{

return PayrollItemType::with('company')
        ->latest()
        ->get();

}





public function find($id)
{

return PayrollItemType::with('company')
        ->findOrFail($id);

}






public function create(array $data)
{

return PayrollItemType::create($data);

}






public function update($id,array $data)
{

$item =
PayrollItemType::findOrFail($id);


$item->update($data);


return $item;

}





public function delete($id)
{

return PayrollItemType::findOrFail($id)
                      ->delete();

}


}

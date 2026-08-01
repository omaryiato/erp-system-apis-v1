<?php

namespace App\Repositories\HR;


use App\Models\HR\EmployeePayrollItem;



class EmployeePayrollItemRepository{


public function all()
{

return EmployeePayrollItem::with([
    'employee',
    'payrollItemType'
])
->latest()
->get();

}




public function find($id)
{

return EmployeePayrollItem::with([
    'employee',
    'payrollItemType'
])
->findOrFail($id);

}





public function create(array $data)
{

return EmployeePayrollItem::create($data);

}





public function update($id,array $data)
{

$item =
EmployeePayrollItem::findOrFail($id);


$item->update($data);


return $item;

}




public function delete($id)
{

return EmployeePayrollItem::findOrFail($id)
->delete();

}







public function employeeItems($employeeId)
{

return EmployeePayrollItem::with(
    'payrollItemType'
)
->where(
    'employee_id',
    $employeeId
)
->get();

}





public function activeItems($employeeId,$date)
{


return EmployeePayrollItem::with(
    'payrollItemType'
)
->where(
    'employee_id',
    $employeeId
)
->whereDate(
    'effective_from',
    '<=',
    $date
)
->where(function($q) use($date){

    $q->whereNull('effective_to')
      ->orWhereDate(
          'effective_to',
          '>=',
          $date
      );

})
->get();


}



}

<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeePayrollItem\AddNewEmployeePayrollItem;
use App\Services\HR\EmployeePayrollItemService;

use App\Http\Requests\HR\EmployeePayrollItem\StoreEmployeePayrollItemRequest;
use App\Http\Requests\HR\EmployeePayrollItem\UpdateEmployeePayrollItem;
use App\Http\Requests\HR\EmployeePayrollItem\UpdateEmployeePayrollItemRequest;

use App\Http\Resources\EmployeePayrollItemResource;



class EmployeePayrollItemController extends Controller
{


public function __construct(
    protected EmployeePayrollItemService $service
)
{}





public function index()
{

return EmployeePayrollItemResource::collection(

$this->service->getAll()

);

}





public function store(
    AddNewEmployeePayrollItem $request
)
{

$item =
$this->service->create(
    $request->validated()
);


return new EmployeePayrollItemResource($item);

}





public function show($id)
{

return new EmployeePayrollItemResource(

$this->service->getById($id)

);

}





public function update(
    UpdateEmployeePayrollItem $request,
    $id
)
{


return new EmployeePayrollItemResource(

$this->service->update(
    $id,
    $request->validated()
)

);


}





public function destroy($id)
{

$this->service->delete($id);


return response()->json([

'message'=>'Employee payroll item deleted'

]);

}





public function employeeItems($employeeId)
{

return EmployeePayrollItemResource::collection(

$this->service->employeeItems(
    $employeeId
)

);

}





public function activeItems($employeeId)
{

return EmployeePayrollItemResource::collection(

$this->service->activeItems(

    $employeeId,

    request('date',now()->format('Y-m-d'))

)

);

}


}

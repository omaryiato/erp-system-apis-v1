<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\PayrollItemType\AddNewPayrollItemType;
use App\Services\HR\PayrollItemTypeService;

use App\Http\Requests\HR\PayrollItemType\StorePayrollItemTypeRequest;
use App\Http\Requests\HR\PayrollItemType\UpdatePayrollItemType;
use App\Http\Requests\HR\PayrollItemType\UpdatePayrollItemTypeRequest;

use App\Http\Resources\PayrollItemTypeResource;



class PayrollItemTypeController extends Controller
{


public function __construct(
    protected PayrollItemTypeService $service
)
{}





public function index()
{

return PayrollItemTypeResource::collection(

$this->service->getAll()

);

}






public function store(
    AddNewPayrollItemType $request
)
{

$item =
$this->service->create(
    $request->validated()
);


return new PayrollItemTypeResource($item);

}






public function show($id)
{

return new PayrollItemTypeResource(

$this->service->getById($id)

);

}






public function update(
    UpdatePayrollItemType $request,
    $id
)
{


return new PayrollItemTypeResource(

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

'message'=>'Payroll item deleted successfully'

]);

}


}

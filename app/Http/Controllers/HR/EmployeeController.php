<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Employee\AddNewEmployee;
use App\Services\HR\EmployeeService;
use App\Http\Requests\HR\Employee\UpdateEmployee;
use App\Http\Resources\EmployeeResource;



class EmployeeController extends Controller
{


public function __construct(
    protected EmployeeService $service
)
{}



public function index()
{

    return EmployeeResource::collection(
        $this->service->getAllEmployee()
    );

}



public function store(AddNewEmployee $request)
{

    $employee = $this->service->addNewEmployee(
        $request->validated()
    );


    return new EmployeeResource($employee);

}




public function show($id)
{

    return new EmployeeResource(
        $this->service->getEmployeeDetails($id)
    );

}




public function update(UpdateEmployee $request,$id)
{

    return new EmployeeResource(
        $this->service->updateEmployeeInfo(
            $id,
            $request->validated()
        )
    );

}




public function destroy($id)
{

    $this->service->deleteEmployee($id);


    return response()->json([
        'message'=>'Employee deleted'
    ]);

}



}

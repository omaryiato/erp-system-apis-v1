<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Attendance\AddNewAttendance;
use App\Services\HR\AttendanceService;

use App\Http\Requests\HR\Attendance\StoreAttendanceRequest;
use App\Http\Requests\HR\Attendance\UpdateAttendance;
use App\Http\Requests\HR\Attendance\UpdateAttendanceRequest;

use App\Http\Resources\AttendanceResource;



class AttendanceController extends Controller
{


public function __construct(
    protected AttendanceService $service
)
{}




public function index()
{

return AttendanceResource::collection(
    $this->service->getAll()
);

}




public function store(
    AddNewAttendance $request
)
{

$attendance =
$this->service->create(
    $request->validated()
);


return new AttendanceResource(
    $attendance
);

}




public function show($id)
{

return new AttendanceResource(
    $this->service->getById($id)
);

}





public function update(
    UpdateAttendance $request,
    $id
)
{

return new AttendanceResource(
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

'message'=>'Attendance deleted successfully'

]);


}





public function employeeAttendance(
    $employeeId
)
{


$data =
$this->service->employeeAttendance(
    $employeeId,
    request('from'),
    request('to')
);



return AttendanceResource::collection(
    $data
);


}



}

<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ChequeRequest;
use App\Http\Resources\ChequeResource;
use App\Models\Cheque;
use App\Services\ChequeService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ChequeController extends Controller
{
    public function __construct(
        protected ChequeService $service
    ) {
    }

    public function index()
    {
        $cheques = $this->service->getAll();

        return ResponseHelper::success(
                ChequeResource::collection($cheques),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                Response::HTTP_OK);

        // $cheques = $this->service->getAll(
        //     $request->only([
        //         'cheque_type',
        //         'status',
        //         'financial_account_id',
        //         'party_type',
        //         'party_id',
        //         'search',
        //         'from_date',
        //         'to_date',
        //     ])
        // );
    }

    public function store(ChequeRequest $request)
    {
        try{

            $cheque = $this->service->create(
                $request->validated()
            );

            return ResponseHelper::success(
                new ChequeResource($cheque),
                [
                    'en' => trans('validation.data_added', [], 'en'),
                    'ar' => trans('validation.data_added', [], 'ar'),
                ],
                Response::HTTP_CREATED);

        } catch(Exception $exception){
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }
    }

    public function show(Cheque $cheque)
    {
        return ResponseHelper::success(
                new ChequeResource($this->service->getDetails($cheque)),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                Response::HTTP_OK);
    }

    public function update(
        ChequeRequest $request,
        Cheque $cheque
    ) {

        try {

            return ResponseHelper::success(
                new ChequeResource(
                    $this->service->update(
                    $cheque,
                    $request->validated()
                )),
                [
                    'en' => trans('validation.update_project', [], 'en'),
                    'ar' => trans('validation.update_project', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return ResponseHelper::error(
                [
                    'en' => trans('validation.exception_error', [], 'en'),
                    'ar' => trans('validation.exception_error', [], 'ar'),
                ],
                $exception->getMessage(),
                500);
        }

    }

    public function destroy(Cheque $cheque)
    {

        return ResponseHelper::success(
                $this->service->delete($cheque),
                [
                    'en' => trans('validation.delete_project', [], 'en'),
                    'ar' => trans('validation.delete_project', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}

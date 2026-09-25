<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FinancialAccountRequest;
use App\Http\Resources\FinancialAccountResource;
use App\Models\FinancialAccount;
use App\Services\FinancialAccountService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class FinancialAccountController extends Controller
{
    public function __construct(
        protected FinancialAccountService $service
    ) {
    }

    public function index()
    {
        //  $request->only([
        //         'account_type',
        //         'is_active',
        //         'search',
        //     ])

        $accounts = $this->service->getAll();

        return ResponseHelper::success(
                FinancialAccountResource::collection($accounts),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                Response::HTTP_OK);
    }

    public function store(FinancialAccountRequest $request)
    {
        try{

            $account = $this->service->create(
                $request->validated()
            );

            return ResponseHelper::success(
                new FinancialAccountResource($account),
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

    public function show(FinancialAccount $account)
    {

        return ResponseHelper::success(
                new FinancialAccountResource($this->service->getDetails($account)),
                [
                    'en' => trans('validation.data_retrieved', [], 'en'),
                    'ar' => trans('validation.data_retrieved', [], 'ar'),
                ],
                Response::HTTP_OK);
    }

    public function update(
        FinancialAccountRequest $request,
        FinancialAccount $account
    ) {

        try {

            return ResponseHelper::success(
                new FinancialAccountResource(
                    $this->service->update(
                    $account,
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

    public function destroy(FinancialAccount $account)
    {
        return ResponseHelper::success(
                $this->service->delete($account),
                [
                    'en' => trans('validation.delete_project', [], 'en'),
                    'ar' => trans('validation.delete_project', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}

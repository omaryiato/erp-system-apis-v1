<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Item\AddNewItem;
use App\Http\Requests\Inventory\Item\UpdateItem;
use App\Http\Resources\Inventory\ItemResource;
use App\Http\Resources\Inventory\ItemSnapshotResource;
use App\Models\Inventory\Item;
use App\Services\Inventory\ItemService;
use App\Services\Inventory\ItemSnapshotService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ItemController extends Controller
{
    public function __construct(
        private ItemService $service,
        private ItemSnapshotService $snapshotService
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    ItemResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_item_list', [], 'en'),
                        'ar' => trans('validation.get_item_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewItem $request
    ) {

        try {

            return ResponseHelper::success(
                    new ItemResource($this->service->create(
                            $request->validated()
                        )),
                    [
                        'en' => trans('validation.add_new_item', [], 'en'),
                        'ar' => trans('validation.add_new_item', [], 'ar'),
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

    public function show(Item $item)
    {
        return ResponseHelper::success(
                new ItemResource($this->service->getDetails($item)),
                [
                    'en' => trans('validation.get_item_details', [], 'en'),
                    'ar' => trans('validation.get_item_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );

    }

    public function update(
        UpdateItem $request,
        Item $item
    ) {


        try {

                return ResponseHelper::success(
                    new ItemResource(
                        $this->service->update(
                            $item,
                            $request->validated()
                        )),
                    [
                        'en' => trans('validation.update_item', [], 'en'),
                        'ar' => trans('validation.update_item', [], 'ar'),
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

    public function destroy(Item $item)
    {
        return ResponseHelper::success(
                $this->service->delete($item),
                [
                    'en' => trans('validation.delete_item', [], 'en'),
                    'ar' => trans('validation.delete_item', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }

    public function snapshots(
        Item $item
    ) {

        return ResponseHelper::success(
                ItemSnapshotResource::collection(
                    $this->snapshotService->getSnapshots($item->id)
                ),
                [
                    'en' => trans('validation.delete_item', [], 'en'),
                    'ar' => trans('validation.delete_item', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }
}

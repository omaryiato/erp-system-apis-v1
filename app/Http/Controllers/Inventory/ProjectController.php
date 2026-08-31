<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\Project\AddNewProject;
use App\Http\Requests\Inventory\Project\UpdateProject;
use App\Http\Resources\Inventory\ProjectResource;
use App\Models\Inventory\Project;
use App\Services\Inventory\ProjectService;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function __construct(
        private ProjectService $service
    ) {}

    public function index()
    {
        return ResponseHelper::success(
                    ProjectResource::collection($this->service->getAll()),
                    [
                        'en' => trans('validation.get_project_list', [], 'en'),
                        'ar' => trans('validation.get_project_list', [], 'ar'),
                    ],
                    Response::HTTP_OK
                );
    }

    public function store(
        AddNewProject $request
    ) {

        try {

            return ResponseHelper::success(
                    new ProjectResource($this->service->create(
                        $request->validated()
                    )),
                    [
                        'en' => trans('validation.add_new_project', [], 'en'),
                        'ar' => trans('validation.add_new_project', [], 'ar'),
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

    public function show(Project $project)
    {
        return ResponseHelper::success(
                new ProjectResource($this->service->getDetails($project->id)),
                [
                    'en' => trans('validation.get_project_details', [], 'en'),
                    'ar' => trans('validation.get_project_details', [], 'ar'),
                ],
                Response::HTTP_OK
            );
    }

    public function update(
        UpdateProject $request,
        Project $project
    ) {

        try {

                return ResponseHelper::success(
                    new ProjectResource(
                        $this->service->update(
                            $project->id,
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

    public function destroy(Project $project)
    {

        return ResponseHelper::success(
                $this->service->delete($project->id),
                [
                    'en' => trans('validation.delete_project', [], 'en'),
                    'ar' => trans('validation.delete_project', [], 'ar'),
                ],
                Response::HTTP_CREATED
            );
    }
}

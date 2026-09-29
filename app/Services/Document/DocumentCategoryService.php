<?php

namespace App\Services\Document;

use App\Models\Document\DocumentCategory;
use App\Repositories\Document\DocumentCategoryRepository;
use Illuminate\Validation\ValidationException;

class DocumentCategoryService
{
    public function __construct(
        protected DocumentCategoryRepository $repository
    ) {
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(DocumentCategory $documentCategory): DocumentCategory {
        return $this->repository->getDetails($documentCategory);
    }

    public function create(
        array $category_request
    ): DocumentCategory {
        return $this->repository->create($this->prepareCategoryInfo($category_request));
    }

    public function update(
        DocumentCategory $documentCategory,
        array $category_request
    ): DocumentCategory {


        return $this->repository->update(
            $documentCategory,
            $this->prepareCategoryInfo($category_request)
        );
    }

    public function delete(DocumentCategory $documentCategory): bool
    {

        return $this->repository->delete($documentCategory);
    }

    public function prepareCategoryInfo(array $category_request)
    {

        $category_data =  [
            'name' => $category_request['name'] ?? null,
            'name_ar' => $category_request['name_ar'] ?? null,
            'code' => $category_request['code'] ?? null,
            'description' => $category_request['description'] ?? null,
            'is_active' => $category_request['is_active'] ?? true,
        ];

        return $category_data;
    }
}

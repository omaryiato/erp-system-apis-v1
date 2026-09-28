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

        $category_request['is_active'] =
            $category_request['is_active'] ?? true;

        $category_request['sort_order'] =
            $category_request['sort_order'] ?? 0;

        return $this->repository->create($category_request);
    }

    public function update(
        DocumentCategory $documentCategory,
        array $category_request
    ): DocumentCategory {


        if (
            isset($category_request['parent_id']) &&
            (int) $category_request['parent_id'] === $documentCategory->id
        ) {
            throw ValidationException::withMessages([
                'parent_id' => [
                    'Category cannot be its own parent.'
                ],
            ]);
        }

        return $this->repository->update(
            $documentCategory,
            $category_request
        );
    }

    public function delete(DocumentCategory $documentCategory): bool
    {

        if (
            $this->repository->hasChildren($documentCategory)
        ) {
            throw ValidationException::withMessages([
                'category' => [
                    'Cannot delete a category that has child categories.'
                ],
            ]);
        }

        if (
            $this->repository->hasDocuments($documentCategory)
        ) {
            throw ValidationException::withMessages([
                'category' => [
                    'Cannot delete a category that has documents.'
                ],
            ]);
        }

        return $this->repository->delete($documentCategory);
    }
}

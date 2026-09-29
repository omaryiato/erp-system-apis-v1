<?php

namespace App\Repositories\Document;

use App\Models\Document\DocumentCategory;

class DocumentCategoryRepository
{
    public function getAll()
    {
        return DocumentCategory::with('documents')->get();
    }

    public function getDetails(DocumentCategory $documentCategory): ?DocumentCategory
    {
        return $documentCategory->load('documents');
    }

    public function create(array $category_request): DocumentCategory
    {
        return DocumentCategory::create($category_request);
    }

    public function update(
        DocumentCategory $documentCategory,
        array $category_request
    ): DocumentCategory {
        $documentCategory->update($category_request);

        return $documentCategory->refresh();
    }

    public function delete(DocumentCategory $documentCategory): bool
    {
        return $documentCategory->delete();
    }

}

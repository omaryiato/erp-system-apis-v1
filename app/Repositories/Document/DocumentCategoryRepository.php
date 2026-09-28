<?php

namespace App\Repositories\Document;

use App\Models\Document\DocumentCategory;
use Illuminate\Database\Eloquent\Collection;

class DocumentCategoryRepository
{
    public function getAll()
    {
        return DocumentCategory::with('parent')->get();
    }

    public function getDetails(DocumentCategory $documentCategory): ?DocumentCategory
    {
        return $documentCategory->load([
                                        'parent',
                                        'children',
                                    ]);
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

    public function codeExists(
        string $code,
        ?int $exceptId = null
    ): bool {
        return DocumentCategory::query()
            ->where('code', $code)
            ->when(
                $exceptId,
                fn ($query) =>
                    $query->where('id', '!=', $exceptId)
            )
            ->exists();
    }

    public function hasDocuments(
        DocumentCategory $documentCategory
    ): bool {
        return $documentCategory->documents()->exists();
    }

    public function hasChildren(
        DocumentCategory $documentCategory
    ): bool {
        return $documentCategory->children()->exists();
    }
}

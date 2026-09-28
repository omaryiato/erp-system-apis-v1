<?php

namespace App\Repositories\Document;

use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository
{
    public function getAll(array $filters = []): Collection
    {
        return Document::query()
            ->with([
                'category',
                'currentVersion',
            ])
            ->when(
                isset($filters['category_id']),
                fn ($query) =>
                    $query->where(
                        'category_id',
                        $filters['category_id']
                    )
            )
            ->when(
                isset($filters['status']),
                fn ($query) =>
                    $query->where(
                        'status',
                        $filters['status']
                    )
            )
            ->when(
                isset($filters['reference_type']),
                fn ($query) =>
                    $query->where(
                        'reference_type',
                        $filters['reference_type']
                    )
            )
            ->when(
                isset($filters['reference_id']),
                fn ($query) =>
                    $query->where(
                        'reference_id',
                        $filters['reference_id']
                    )
            )
            ->when(
                !empty($filters['search']),
                fn ($query) =>
                    $query->where(function ($q) use ($filters) {
                        $q->where(
                            'title',
                            'ILIKE',
                            '%' . $filters['search'] . '%'
                        )
                        ->orWhere(
                            'document_code',
                            'ILIKE',
                            '%' . $filters['search'] . '%'
                        );
                    })
            )
            ->latest('id')
            ->get();
    }

    public function findById(int $id): ?Document
    {
        return Document::query()
            ->with([
                'category',
                'currentVersion',
                'versions',
            ])
            ->find($id);
    }

    public function create(array $data): Document
    {
        return Document::create($data);
    }

    public function update(
        Document $document,
        array $data
    ): Document {
        $document->update($data);

        return $document->refresh()->load([
            'category',
            'currentVersion',
        ]);
    }

    public function delete(Document $document): bool
    {
        return $document->delete();
    }
}

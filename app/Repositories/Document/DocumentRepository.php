<?php

namespace App\Repositories\Document;

use App\Models\Document\Document;

class DocumentRepository
{
    public function getAll()
    {
        return Document::with([
                'category',
                'versions',
            ])->get();
    }

    public function getDetails(Document $document): ?Document
    {
        return $document->load([
                'category',
                'versions',
            ]);
    }

    public function create(array $document_request): Document
    {
        return Document::create($document_request);
    }

    public function update(
        Document $document,
        array $document_request
    ): Document {
        $document->update($document_request);

        return $document->refresh();
    }

    public function delete(Document $document): bool
    {
        return $document->delete();
    }
}

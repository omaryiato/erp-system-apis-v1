<?php

namespace App\Repositories\Document;

use App\Models\Document\DocumentVersion;
use Illuminate\Database\Eloquent\Collection;

class DocumentVersionRepository
{
    public function getByDocument(
        int $documentId
    ): Collection {
        return DocumentVersion::query()
            ->where('document_id', $documentId)
            ->orderByDesc('version_number')
            ->get();
    }

    public function findById(
        int $id
    ): ?DocumentVersion {
        return DocumentVersion::query()
            ->with('document')
            ->find($id);
    }

    public function create(
        array $data
    ): DocumentVersion {
        return DocumentVersion::create($data);
    }

    public function getNextVersionNumber(
        int $documentId
    ): int {
        $lastVersion = DocumentVersion::query()
            ->where('document_id', $documentId)
            ->max('version_number');

        return ((int) $lastVersion) + 1;
    }

    public function delete(
        DocumentVersion $version
    ): bool {
        return $version->delete();
    }
}

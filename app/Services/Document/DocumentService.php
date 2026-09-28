<?php

namespace App\Services\Document;

use App\Models\Document\Document;
use App\Models\Document\DocumentVersion;
use App\Repositories\Document\DocumentRepository;
use App\Repositories\Document\DocumentVersionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocumentService
{
    public function __construct(
        protected DocumentRepository $repository,
        protected DocumentVersionRepository $versionRepository
    ) {
    }

    public function getAll(
        array $filters = []
    ): Collection {
        return $this->repository->getAll($filters);
    }

    public function findById(
        int $id
    ): Document {
        $document = $this->repository->findById($id);

        if (!$document) {
            throw ValidationException::withMessages([
                'document' => [
                    'Document not found.'
                ],
            ]);
        }

        return $document;
    }

    public function create(
        array $data
    ): Document {
        $data['status'] =
            $data['status'] ?? 'ACTIVE';

        return $this->repository->create($data)
            ->load([
                'category',
                'currentVersion',
            ]);
    }

    public function update(
        int $id,
        array $data
    ): Document {
        $document = $this->findById($id);

        if ($document->status === 'DELETED') {
            throw ValidationException::withMessages([
                'document' => [
                    'Deleted document cannot be updated.'
                ],
            ]);
        }

        return $this->repository->update(
            $document,
            $data
        );
    }

    public function delete(int $id): bool
    {
        $document = $this->findById($id);

        /*
         * Logical delete.
         *
         * We keep the document and its versions
         * for history.
         */
        return $this->repository->update(
            $document,
            [
                'status' => 'DELETED',
            ]
        ) !== null;
    }

    public function uploadVersion(
        int $documentId,
        UploadedFile $file,
        ?string $description = null
    ): DocumentVersion {
        return DB::transaction(function () use (
            $documentId,
            $file,
            $description
        ) {
            $document = $this->findById($documentId);

            if ($document->status === 'DELETED') {
                throw ValidationException::withMessages([
                    'document' => [
                        'Cannot add a version to a deleted document.'
                    ],
                ]);
            }

            $versionNumber =
                $this->versionRepository
                    ->getNextVersionNumber($documentId);

            $disk = 'local';

            $directory =
                'documents/' .
                $documentId;

            $extension =
                $file->getClientOriginalExtension();

            $fileName =
                'v' .
                $versionNumber .
                '_' .
                uniqid() .
                ($extension
                    ? '.' . $extension
                    : '');

            $path = $file->storeAs(
                $directory,
                $fileName,
                $disk
            );

            $version =
                $this->versionRepository->create([
                    'document_id' =>
                        $documentId,

                    'version_number' =>
                        $versionNumber,

                    'file_name' =>
                        $fileName,

                    'original_file_name' =>
                        $file->getClientOriginalName(),

                    'file_path' =>
                        $path,

                    'storage_disk' =>
                        $disk,

                    'mime_type' =>
                        $file->getMimeType(),

                    'file_extension' =>
                        $extension,

                    'file_size' =>
                        $file->getSize(),

                    'file_hash' =>
                        hash_file(
                            'sha256',
                            $file->getRealPath()
                        ),

                    'description' =>
                        $description,

                    'uploaded_by' => auth()->id(),

                    'created_at' =>
                        now(),
                ]);

            /*
             * Make this version the current version.
             */
            $document->update([
                'current_version_id' =>
                    $version->id,
            ]);

            return $version->load('document');
        });
    }

    public function getVersions(
        int $documentId
    ): Collection {
        $this->findById($documentId);

        return $this->versionRepository
            ->getByDocument($documentId);
    }

    public function downloadVersion(
        int $documentId,
        int $versionId
    ) {
        $document = $this->findById($documentId);

        $version = DocumentVersion::query()
            ->where('document_id', $document->id)
            ->where('id', $versionId)
            ->first();

        if (!$version) {
            throw ValidationException::withMessages([
                'version' => [
                    'Document version not found.'
                ],
            ]);
        }

        if (
            !Storage::disk(
                $version->storage_disk
            )->exists($version->file_path)
        ) {
            throw ValidationException::withMessages([
                'file' => [
                    'Document file not found in storage.'
                ],
            ]);
        }

        return Storage::disk(
            $version->storage_disk
        )->download(
            $version->file_path,
            $version->original_file_name
        );
    }
}

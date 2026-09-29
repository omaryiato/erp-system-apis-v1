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

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getDetails(
        Document $document
    ): Document {
        return $this->repository->getDetails($document);
    }

    public function create(
        array $document_request,
        object $request
    ): Document {

        return DB::transaction(function () use ($document_request, $request) {

            $document = $this->repository->create($this->prepareDocumentInfo($document_request));

            $this->uploadVersion($document, $request->file('file'), $document_request['description']);

            return $document->load([
                    'category',
                    'versions',
                ])->refresh();
        });
    }

    public function update(
        Document $document,
        array $document_request,
        object $request
    ): Document {

        return DB::transaction(function () use ($document, $document_request, $request) {

            $document = $this->repository->update(
                $document,
                $this->prepareDocumentInfo($document_request)
            );

            $this->uploadVersion($document, $request->file('file'), $document_request['description']);

            return $document->load([
                        'category',
                        'versions',
                    ]);
        });
    }

    public function delete(Document $document): bool
    {

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
        Document $document,
        UploadedFile $file,
        ?string $description = null
    ): DocumentVersion {
        return DB::transaction(function () use (
            $document,
            $file,
            $description
        ) {

            if ($document->status === 'DELETED') {
                throw ValidationException::withMessages([
                    'document' => [
                        'Cannot add a version to a deleted document.'
                    ],
                ]);
            }

            $versionNumber =
                $this->versionRepository
                    ->getNextVersionNumber($document->id);

            // $disk = 'local';
            $disk = 'public';

            $directory =
                'storage/documents/' .
                $document->reference_type .'/'.
                $document->reference_id .'/'.
                $document->id;

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
                        $document->id,

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

                    'uploaded_by' => $document->updated_by,

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

    public function prepareDocumentInfo(array $document_request)
    {
        $document_data =  [
            'category_id' => $document_request['category_id'] ?? null,
            'title' => $document_request['title'] ?? null,
            'description' => $document_request['description'] ?? null,
            'invoice_date' => $document_request['invoice_date'] ?? null,
            'invoice_amount' => $document_request['invoice_amount'] ?? 0,
            'invoice_number' => $document_request['invoice_number'] ?? null,
            'document_code' => $document_request['document_code'] ?? null,
            'status' => $document_request['status'] ?? 'ACTIVE',
            'reference_type' => $document_request['reference_type'] ?? null,
            'reference_id' => $document_request['reference_id'] ?? null,
        ];

        if (isset($document_request['created_by'])) {
            $document_data['created_by'] = $document_request['created_by'];
        }

        if (isset($document_request['updated_by'])) {
            $document_data['updated_by'] = $document_request['updated_by'];
        }

        return $document_data;
    }

    public function getDocumentVersion(
    Document $document,
    DocumentVersion $documentVersion
    ): array {
        $version = DocumentVersion::query()
            ->where('document_id', $document->id)
            ->where('id', $documentVersion->id)
            ->first();

        if (!$version) {
            throw ValidationException::withMessages([
                'version' => [
                    'Document version not found.'
                ],
            ]);
        }

        $disk = Storage::disk($version->storage_disk);

        if (!$disk->exists($version->file_path)) {
            throw ValidationException::withMessages([
                'file' => [
                    'Document file not found in storage.'
                ],
            ]);
        }

        return [
            'path' => $disk->path($version->file_path),
            'file_name' => $version->original_file_name,
            'mime_type' => $version->mime_type,
        ];
    }

}

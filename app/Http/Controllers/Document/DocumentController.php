<?php

namespace App\Http\Controllers\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentRequest;
use App\Http\Requests\Document\DocumentVersionRequest;
use App\Http\Resources\Document\DocumentResource;
use App\Http\Resources\Document\DocumentVersionResource;
use App\Services\Document\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentService $service
    ) {
    }

    public function index(Request $request)
    {
        $documents = $this->service->getAll(
            $request->only([
                'category_id',
                'status',
                'reference_type',
                'reference_id',
                'search',
            ])
        );

        return DocumentResource::collection(
            $documents
        );
    }

    public function store(
        DocumentRequest $request
    ) {
        $document = $this->service->create(
            $request->validated()
        );

        return new DocumentResource(
            $document
        );
    }

    public function show(int $id)
    {
        return new DocumentResource(
            $this->service->findById($id)
        );
    }

    public function update(
        DocumentRequest $request,
        int $id
    ) {
        $document = $this->service->update(
            $id,
            $request->validated()
        );

        return new DocumentResource(
            $document
        );
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'message' =>
                'Document deleted successfully.',
        ]);
    }

    public function uploadVersion(
        DocumentVersionRequest $request,
        int $id
    ) {
        $version = $this->service->uploadVersion(
            $id,
            $request->file('file'),
            $request->validated()['description'] ?? null
        );

        return new DocumentVersionResource(
            $version
        );
    }

    public function versions(int $id)
    {
        $versions = $this->service->getVersions($id);

        return DocumentVersionResource::collection(
            $versions
        );
    }

    public function downloadVersion(
        int $id,
        int $versionId
    ) {
        return $this->service->downloadVersion(
            $id,
            $versionId
        );
    }
}

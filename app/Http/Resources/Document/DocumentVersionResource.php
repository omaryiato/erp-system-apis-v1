<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DocumentVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,

            'document_id' => $this->document_id ?? null,

            'version_number' => $this->version_number ?? null,

            'file_name' => $this->file_name ?? null,

            'original_file_name' =>
                $this->original_file_name ?? null,

            'file_path' =>   asset($this->file_path) ?? null,

            // 'file_path' => $this->file_path ?? null,

            'storage_disk' => $this->storage_disk ?? null,

            'mime_type' => $this->mime_type ?? null,

            'file_extension' =>
                $this->file_extension ?? null,

            'file_size' => $this->file_size ?? null,

            'file_hash' => $this->file_hash ?? null,

            'description' => $this->description ?? null,

            'uploaded_by' => $this->uploaded_by ?? null,

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ) ?? null,

            // 'file_url' => route(
            //     'get.document.version',
            //     [
            //         'document' => $this->document_id,
            //         'documentVersion' => $this->id,
            //     ]
            // ),
        ];
    }
}

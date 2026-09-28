<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'document_id' => $this->document_id,

            'version_number' => $this->version_number,

            'file_name' => $this->file_name,

            'original_file_name' =>
                $this->original_file_name,

            'file_path' => $this->file_path,

            'storage_disk' => $this->storage_disk,

            'mime_type' => $this->mime_type,

            'file_extension' =>
                $this->file_extension,

            'file_size' => $this->file_size,

            'file_hash' => $this->file_hash,

            'description' => $this->description,

            'uploaded_by' => $this->uploaded_by,

            'created_at' => $this->created_at,
        ];
    }
}

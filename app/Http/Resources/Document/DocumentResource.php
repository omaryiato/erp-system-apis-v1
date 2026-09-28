<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'category_id' => $this->category_id,

            'category' => new DocumentCategoryResource(
                $this->whenLoaded('category')
            ),

            'title' => $this->title,

            'description' => $this->description,

            'document_code' => $this->document_code,

            'current_version_id' =>
                $this->current_version_id,

            'status' => $this->status,

            'reference_type' =>
                $this->reference_type,

            'reference_id' =>
                $this->reference_id,

            'current_version' =>
                new DocumentVersionResource(
                    $this->whenLoaded('currentVersion')
                ),

            'versions' =>
                DocumentVersionResource::collection(
                    $this->whenLoaded('versions')
                ),

            'created_by' => $this->created_by,

            'updated_by' => $this->updated_by,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}

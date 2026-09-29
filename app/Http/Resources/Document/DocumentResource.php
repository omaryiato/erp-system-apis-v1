<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,

            'category_id' => $this->category_id ?? null,

            'category' => new DocumentCategoryResource(
                $this->whenLoaded('category')
            ) ?? null,

            'title' => $this->title ?? null,

            'description' => $this->description ?? null,

            'document_code' => $this->document_code ?? null,

            'current_version_id' =>
                $this->current_version_id ?? null,

            'status' => $this->status ?? null,

            'reference_type' =>
                $this->reference_type ?? null,

            'reference_id' =>
                $this->reference_id ?? null,

            // 'current_version' =>
            //     new DocumentVersionResource(
            //         $this->whenLoaded('currentVersion')
            //     ) ?? null,

            'versions' =>
                DocumentVersionResource::collection(
                    $this->whenLoaded('versions')
                ) ?? null,

            'created_by' => $this->created_by ?? null,

            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ) ?? null,

            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ) ?? null,
        ];
    }
}

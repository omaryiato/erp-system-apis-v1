<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'name_ar' => $this->name_ar,

            'code' => $this->code,

            'description' => $this->description,

            'is_active' => $this->is_active,

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ),
            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ),
        ];
    }
}

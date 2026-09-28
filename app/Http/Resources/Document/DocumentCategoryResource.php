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

            'parent_id' => $this->parent_id,

            'name' => $this->name,
            'name_ar' => $this->name_ar,

            'code' => $this->code,

            'description' => $this->description,

            'is_active' => $this->is_active,

            'sort_order' => $this->sort_order,

            'parent' => new self(
                $this->whenLoaded('parent')
            ),

            'children' => self::collection(
                $this->whenLoaded('children')
            ),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

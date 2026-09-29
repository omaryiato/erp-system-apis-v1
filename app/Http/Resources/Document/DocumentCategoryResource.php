<?php

namespace App\Http\Resources\Document;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null ,

            'name' => $this->name ?? null ,
            'name_ar' => $this->name_ar ?? null ,

            'code' => $this->code ?? null ,

            'description' => $this->description ?? null ,

            'is_active' => $this->is_active ?? null ,

            'created_at' => $this->created_at?->format(
                'Y-m-d H:i:s'
            ) ?? null ,
            'updated_at' => $this->updated_at?->format(
                'Y-m-d H:i:s'
            ) ?? null ,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LookupValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id ?? null,

            'type_id' => $this->type_id ?? null,
            'type_en_name' => $this->type?->type_en_name ?? null,
            'type_ar_name' => $this->type?->type_ar_name ?? null,

            'code' => $this->code ?? null,
            'color' => $this->color ?? null,
            'en_meaning' => $this->en_meaning ?? null,
            'ar_meaning' => $this->ar_meaning ?? null,
            'description' => $this->description ?? null,
            'status' => $this->status ?? null,

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,
        ];
    }
}

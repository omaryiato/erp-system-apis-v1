<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\LookupValue;


class LookupTypeResource extends JsonResource
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
            'type_en_name' => $this->type_en_name ?? null,
            'type_ar_name' => $this->type_ar_name ?? null,
            'type_description' => $this->type_description ?? null,
            'status' => $this->status ?? null,

            'created_by' => $this->created_by ?? null,
            'updated_by' => $this->updated_by ?? null,

            'created_at' => $this->created_at?->format('Y-m-d H:i:s') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s') ?? null,
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s') ?? null,

            'lookup_values' => LookupValueResource::collection($this->whenLoaded('values')),
        ];
    }
}

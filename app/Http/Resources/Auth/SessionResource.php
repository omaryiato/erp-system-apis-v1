<?php

declare(strict_types=1);

namespace App\Http\Resources\Auth;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class SessionResource extends UserResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        unset($data['user_status']);
        $data['status'] = $this->status;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        $data['deleted_at'] = $this->deleted_at;

        return $data;
    }
}

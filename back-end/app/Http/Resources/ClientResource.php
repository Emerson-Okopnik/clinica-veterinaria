<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'cpf' => $this->cpf,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'pets' => PetResource::collection($this->whenLoaded('pets')),
            'pets_count' => $this->pets_count ?? $this->pets->count(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

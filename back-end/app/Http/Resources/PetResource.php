<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'species' => $this->species,
            'breed' => $this->breed,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'age' => $this->age,
            'weight' => $this->weight,
            'color' => $this->color,
            'microchip' => $this->microchip,
            'photo' => $this->photo,
            'notes' => $this->notes,
            'client' => new ClientResource($this->whenLoaded('client')),
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
            'medical_records' => MedicalRecordResource::collection($this->whenLoaded('medicalRecords')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

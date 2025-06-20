<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'prescription' => $this->prescription,
            'observations' => $this->observations,
            'symptoms' => $this->symptoms,
            'vital_signs' => $this->vital_signs,
            'weight' => $this->weight,
            'temperature' => $this->temperature,
            'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
            'pet' => new PetResource($this->whenLoaded('pet')),
            'veterinarian' => [
                'id' => $this->veterinarian->id,
                'name' => $this->veterinarian->name,
                'crmv' => $this->veterinarian->crmv,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

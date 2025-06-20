<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'scheduled_at' => $this->scheduled_at->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'reason' => $this->reason,
            'notes' => $this->notes,
            'price' => $this->price,
            'duration' => $this->duration,
            'pet' => new PetResource($this->whenLoaded('pet')),
            'veterinarian' => [
                'id' => $this->veterinarian->id,
                'name' => $this->veterinarian->name,
                'crmv' => $this->veterinarian->crmv,
            ],
            'client' => $this->whenLoaded('pet.client', function () {
                return new ClientResource($this->pet->client);
            }),
            'medical_record' => new MedicalRecordResource($this->whenLoaded('medicalRecord')),
            'payment' => $this->whenLoaded('payment'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

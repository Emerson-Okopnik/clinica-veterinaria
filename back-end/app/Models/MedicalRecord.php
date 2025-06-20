<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'pet_id',
        'veterinarian_id',
        'diagnosis',
        'treatment',
        'prescription',
        'observations',
        'symptoms',
        'vital_signs',
        'weight',
        'temperature'
    ];

    protected $casts = [
        'vital_signs' => 'array',
        'weight' => 'decimal:2',
        'temperature' => 'decimal:1',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }
}

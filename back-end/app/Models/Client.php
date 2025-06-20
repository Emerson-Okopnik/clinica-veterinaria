<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'cpf',
        'birth_date',
        'notes'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class);
    }

    public function appointments()
    {
        return $this->hasManyThrough(Appointment::class, Pet::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

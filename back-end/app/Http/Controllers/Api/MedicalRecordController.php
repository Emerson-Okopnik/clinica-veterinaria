<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with('appointment.pet.client', 'veterinarian');

        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }

        if ($request->has('veterinarian_id')) {
            $query->where('veterinarian_id', $request->veterinarian_id);
        }

        $records = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 15));

        return MedicalRecordResource::collection($records);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:users,id',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'observations' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $record = MedicalRecord::create($request->all());
        $record->load('appointment.pet.client', 'veterinarian');

        return new MedicalRecordResource($record);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load('appointment.pet.client', 'veterinarian');
        return new MedicalRecordResource($medicalRecord);
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validator = Validator::make($request->all(), [
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'prescription' => 'nullable|string',
            'observations' => 'nullable|string',
            'symptoms' => 'nullable|string',
            'vital_signs' => 'nullable|array',
            'weight' => 'nullable|numeric|min:0',
            'temperature' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $medicalRecord->update($request->all());
        $medicalRecord->load('appointment.pet.client', 'veterinarian');

        return new MedicalRecordResource($medicalRecord);
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();
        return response()->json(['message' => 'Medical record deleted successfully']);
    }
}

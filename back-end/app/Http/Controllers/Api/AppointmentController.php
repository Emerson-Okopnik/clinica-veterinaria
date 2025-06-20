<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['pet.client', 'veterinarian']);

        if ($request->has('date') && $request->date) {
            $query->whereDate('scheduled_at', $request->date);
        }

        if ($request->has('veterinarian_id') && $request->veterinarian_id) {
            $query->where('veterinarian_id', $request->veterinarian_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('pet_id') && $request->pet_id) {
            $query->where('pet_id', $request->pet_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pet', function ($petQuery) use ($search) {
                    $petQuery->where('name', 'ILIKE', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('name', 'ILIKE', "%{$search}%");
                        });
                })->orWhere('reason', 'ILIKE', "%{$search}%");
            });
        }

        $appointments = $query->orderBy('scheduled_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return AppointmentResource::collection($appointments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pet_id' => 'required|exists:pets,id',
            'veterinarian_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:15|max:480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $scheduledAt = $request->scheduled_at;
        $veterinarianId = $request->veterinarian_id;
        $duration = $request->duration ?? 60;

        $endTime = date('Y-m-d H:i:s', strtotime($scheduledAt . ' +' . $duration . ' minutes'));

        $conflict = Appointment::where('veterinarian_id', $veterinarianId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($scheduledAt, $endTime) {
                $query->whereBetween('scheduled_at', [$scheduledAt, $endTime])
                    ->orWhere(function ($q) use ($scheduledAt, $endTime) {
                        $q->where('scheduled_at', '<=', $scheduledAt)
                            ->whereRaw("scheduled_at + (duration || ' minutes')::interval >= ?", [$scheduledAt]);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'Time slot already booked'], 422);
        }

        $appointment = Appointment::create(array_merge($request->all(), [
            'status' => 'scheduled',
            'duration' => $duration,
        ]));

        $appointment->load('pet.client', 'veterinarian');

        return new AppointmentResource($appointment);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('pet.client', 'veterinarian', 'medicalRecord', 'payment');
        return new AppointmentResource($appointment);
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'scheduled_at' => 'sometimes|date',
            'status' => 'sometimes|in:scheduled,in_progress,completed,cancelled',
            'reason' => 'sometimes|string|max:500',
            'notes' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:15|max:480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('scheduled_at') && $request->scheduled_at !== $appointment->scheduled_at) {
            $scheduledAt = $request->scheduled_at;
            $veterinarianId = $request->veterinarian_id ?? $appointment->veterinarian_id;
            $duration = $request->duration ?? $appointment->duration ?? 60;

            $endTime = date('Y-m-d H:i:s', strtotime($scheduledAt . ' +' . $duration . ' minutes'));

            $conflict = Appointment::where('veterinarian_id', $veterinarianId)
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($scheduledAt, $endTime) {
                    $query->whereBetween('scheduled_at', [$scheduledAt, $endTime])
                        ->orWhere(function ($q) use ($scheduledAt, $endTime) {
                            $q->where('scheduled_at', '<=', $scheduledAt)
                                ->whereRaw("scheduled_at + (duration || ' minutes')::interval >= ?", [$scheduledAt]);
                        });
                })
                ->exists();

            if ($conflict) {
                return response()->json(['error' => 'Time slot already booked'], 422);
            }
        }

        $appointment->update($request->all());
        $appointment->load('pet.client', 'veterinarian');

        return new AppointmentResource($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}

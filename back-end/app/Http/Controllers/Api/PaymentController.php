<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('appointment.pet.client');

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')
                         ->paginate($request->get('per_page', 15));

        return response()->json($payments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'appointment_id' => 'required|exists:appointments,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,pix,transfer',
            'status' => 'sometimes|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payment = Payment::create(array_merge($request->all(), [
            'status' => $request->status ?? 'pending',
            'paid_at' => $request->status === 'paid' ? now() : null,
        ]));

        $payment->load('appointment.pet.client');

        return response()->json($payment);
    }

    public function show(Payment $payment)
    {
        $payment->load('appointment.pet.client');
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|numeric|min:0',
            'payment_method' => 'sometimes|in:cash,card,pix,transfer',
            'status' => 'sometimes|in:pending,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $updateData = $request->all();
        
        if ($request->status === 'paid' && $payment->status !== 'paid') {
            $updateData['paid_at'] = now();
        } elseif ($request->status !== 'paid') {
            $updateData['paid_at'] = null;
        }

        $payment->update($updateData);
        $payment->load('appointment.pet.client');

        return response()->json($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Pet;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dashboardStats()
    {
        $totalClients = Client::count();
        $totalPets = Pet::count();
        $todayAppointments = Appointment::whereDate('scheduled_at', today())->count();
        
        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        return response()->json([
            'totalClients' => $totalClients,
            'totalPets' => $totalPets,
            'todayAppointments' => $todayAppointments,
            'monthlyRevenue' => number_format($monthlyRevenue, 2, ',', '.')
        ]);
    }

    public function appointmentsByPeriod(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $appointments = DB::table('appointments')
            ->select([
                DB::raw('DATE(scheduled_at) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = \'completed\' THEN 1 ELSE 0 END) as completed'),
                DB::raw('SUM(CASE WHEN status = \'cancelled\' THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('COALESCE(SUM(CASE WHEN status = \'completed\' THEN price ELSE 0 END), 0) as revenue')
            ])
            ->whereBetween('scheduled_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy(DB::raw('DATE(scheduled_at)'))
            ->orderBy('date')
            ->get();

        return response()->json($appointments);
    }

    public function appointmentsByVeterinarian(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $appointments = DB::table('appointments')
            ->select([
                'appointments.veterinarian_id',
                'users.name as veterinarian_name',
                DB::raw('COUNT(*) as total')
            ])
            ->join('users', 'appointments.veterinarian_id', '=', 'users.id')
            ->whereBetween('appointments.scheduled_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('appointments.veterinarian_id', 'users.name')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($appointments);
    }

    public function petsByClient()
    {
        $clients = DB::table('clients')
            ->select([
                'clients.id as client_id',
                'clients.name as client_name',
                DB::raw('COUNT(pets.id) as pet_count')
            ])
            ->leftJoin('pets', 'clients.id', '=', 'pets.client_id')
            ->groupBy('clients.id', 'clients.name')
            ->havingRaw('COUNT(pets.id) > 0')
            ->orderByRaw('COUNT(pets.id) DESC')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'client_id' => $item->client_id,
                    'client_name' => $item->client_name,
                    'total' => $item->pet_count
                ];
            });

        return response()->json($clients);
    }
}

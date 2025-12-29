<?php

namespace App\Http\Controllers;

use App\Models\DanaDKM;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // total peserta kurban
        $total_peserta = User::where('role', 'peserta_kurban')->count();

        // total daging
        $total_berat_daging = Order::where('status', 'disetujui')->sum('perkiraan_daging');

        // pendapatan kurban
        $total_pendapatan = DanaDKM::sum('jumlah_dana');


        // Ambil semua status dan hitung sekaligus
        $statusCounts = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalOrders = array_sum($statusCounts);

        // Inisialisasi semua status dengan 0
        $orders = [
            'total' => $totalOrders,
            'ditolak' => ['count' => 0, 'percent' => 0],
            'menunggu verifikasi' => ['count' => 0, 'percent' => 0],
            'disetujui' => ['count' => 0, 'percent' => 0],
        ];

        // Isi dengan data real
        foreach ($statusCounts as $status => $count) {
            $percent = $totalOrders > 0 ? round(($count / $totalOrders) * 100, 1) : 0;

            if (isset($orders[$status])) {
                $orders[$status] = [
                    'count' => $count,
                    'percent' => $percent
                ];
            }
        }

        return view('admin/dashboard', compact('total_peserta', 'total_berat_daging', 'total_pendapatan', 'user', 'orders'));
    }
}

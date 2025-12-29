<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        $orders = Order::with('user', 'ketersediaanHewan')
            ->paginate(5);

        return view('admin.order.index', compact('orders', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Store Order - Request Data:', $request->all());

        // Validasi dasar
        $rules = [
            'tipe_pendaftaran' => 'required|in:transfer,kirim langsung',
            'total_hewan' => 'required|integer|min:1',
            'berat_hewan' => 'required|numeric|min:1',
            'perkiraan_daging' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'jenis_hewan' => 'required|string|max:100',
        ];

        // Aturan khusus transfer
        if ($request->tipe_pendaftaran === 'transfer') {
            $rules['ketersediaan_hewan_id'] = 'required|exists:ketersediaan_hewan,id';
            $rules['bukti_pembayaran'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048';
        }

        $data = $request->validate($rules, [
            'berat_hewan.required' => 'Berat hewan harus diisi',
            'perkiraan_daging.required' => 'Perkiraan daging harus dihitung',
            'total_harga.required' => 'Total harga harus dihitung',
            'jenis_hewan.required' => 'Jenis hewan harus diisi',
        ]);

        \Log::info('Store Order - Validated Data:', $data);

        DB::beginTransaction();

        try {
            // Data wajib
            $data['user_id'] = auth()->id();
            $data['status'] = 'menunggu verifikasi';

            if ($data['tipe_pendaftaran'] === 'transfer') {
                $hewan = KetersediaanHewan::findOrFail($data['ketersediaan_hewan_id']);

                // Validasi stok
                if ($hewan->jumlah < $data['total_hewan']) {
                    return back()->withInput()
                        ->with('error', 'Stok tidak cukup. Tersedia: ' . $hewan->jumlah . ' ekor');
                }

                // Override dengan data dari database (untuk snapshot konsisten)
                $data['jenis_hewan'] = $hewan->jenis_hewan;
                $data['berat_hewan'] = $hewan->bobot;

                // Hitung ulang untuk memastikan konsistensi
                $data['total_harga'] = $hewan->harga * $data['total_hewan'];
                $data['perkiraan_daging'] = $hewan->bobot * $data['total_hewan'] * 0.4;

                // Upload bukti pembayaran
                // Gunakan path absolute yang berbeda
                if ($request->hasFile('bukti_pembayaran')) {
                    $file = $request->file('bukti_pembayaran');
                    $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Gunakan path yang benar-benar baru
                    $customPath = storage_path('app/public/bukti_pembayaran_v2');

                    if (!file_exists($customPath)) {
                        mkdir($customPath, 0755, true);
                    }

                    // Simpan dengan nama yang unik
                    $fullPath = $customPath . '/' . $filename;
                    $file->move($customPath, $filename);

                    \Log::info('Custom Path Debug:', [
                        'custom_path' => $customPath,
                        'full_path' => $fullPath,
                        'file_exists' => file_exists($fullPath) ? 'YES' : 'NO',
                        'files_in_dir' => scandir($customPath)
                    ]);

                    // Simpan path relatif
                    $data['bukti_pembayaran'] = 'bukti_pembayaran_v2/' . $filename;
                }
                // Kurangi stok
                $hewan->decrement('jumlah', $data['total_hewan']);

                // Update status jika habis
                if ($hewan->jumlah <= 0) {
                    $hewan->update(['status' => 'habis']);
                }
            } else {
                // Kirim langsung
                $data['ketersediaan_hewan_id'] = null;
                $data['bukti_pembayaran'] = null;

                // Untuk kirim langsung, total_harga = 0
                $data['total_harga'] = 0;

                // Pastikan perkiraan daging sudah benar
                if (!isset($data['perkiraan_daging']) || $data['perkiraan_daging'] <= 0) {
                    $data['perkiraan_daging'] = $data['berat_hewan'] * $data['total_hewan'] * 0.4;
                }
            }

            \Log::info('Store Order - Final Data untuk Database:', $data);

            // Create order
            $order = Order::create($data);

            DB::commit();

            \Log::info('Store Order - Success! Order ID: ' . $order->id);

            return redirect()
                ->back()
                ->with('success', 'Pendaftaran berhasil! Order ID: ' . $order->id)
                ->with('order_id', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Store Order - Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data ?? []
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Di OrderController.php
    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'disetujui', // Match dengan ENUM value
                'alasan_penolakan' => $request->alasan_penolakan,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'alasan_penolakan' => $request->alasan_penolakan,
                'rejected_at' => null,
                'rejected_by' => null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Order $order)
    {

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Jika order transfer, kembalikan stok
            if ($order->tipe_pendaftaran === 'transfer' && $order->ketersediaan_hewan_id) {
                $hewan = KetersediaanHewan::find($order->ketersediaan_hewan_id);
                if ($hewan) {
                    $hewan->increment('jumlah', $order->total_hewan);

                    if ($hewan->status == 'habis') {
                        $hewan->update(['status' => 'tersedia']);
                    }
                }
            }

            $order->update([
                'status' => 'ditolak', // Match dengan ENUM value
                'alasan_penolakan' => $request->alasan_penolakan,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'verification_note' => null,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menolak order: ' . $e->getMessage());
        }
    }

    // route baru
    public function verifikasi()
    {
        // Hanya tampilkan order dengan status 'menunggu_verifikasi'
        $orders = Order::with(['user', 'ketersediaanHewan'])
            ->where('status', 'menunggu verifikasi')
            ->latest()
            ->paginate(10);

        return view('admin/order/persetujuan', compact('orders'));
    }

    // Optional: untuk status lainnya
    public function approved()
    {
        $orders = Order::with(['user', 'ketersediaanHewan'])
            ->where('status', 'disetujui')
            ->latest()
            ->paginate(10);

        return view('admin/order/approved', compact('orders'));
    }

    public function rejected()
    {
        $orders = Order::with(['user',  'ketersediaanHewan'])
            ->where('status', 'ditolak')
            ->whereNotNull('alasan_penolakan')
            ->latest()
            ->paginate(10);

        return view('admin/order/rejected', compact('orders'));
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function __construct()
    {
        // Setup Config Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-YOUR_KEY_HERE');
        \Midtrans\Config::$isProduction = false; 
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function index()
    {
       
        $tagihan = Tagihan::with(['siswa.kelas', 'sppMaster', 'pembayaranAktif'])
                    ->latest()
                    ->get();
                    
        return view('admin.pembayaran.index', compact('tagihan'));
    }

    public function generateVa(Request $request, $tagihan_id)
    {
        $request->validate(['bank' => 'required|in:bca,bni,bri']);
        
        $tagihan = Tagihan::with('siswa')->findOrFail($tagihan_id);

       
        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan ini sudah lunas!');
        }

       
        $vaAktif = Pembayaran::where('tagihan_id', $tagihan->id)->where('status', 'menunggu')->first();
        if ($vaAktif) {
            return back()->with('error', 'Tagihan ini masih memiliki Virtual Account aktif (Menunggu Pembayaran).');
        }

        DB::beginTransaction();
        try {
           
            $orderId = 'SPP-' . $tagihan->id . '-' . time();

           
            $params = [
                'payment_type' => 'bank_transfer',
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $tagihan->nominal,
                ],
                'bank_transfer' => [
                    'bank' => $request->bank 
                ],
                'customer_details' => [
                    'first_name' => $tagihan->siswa->nama,
                    'email' => 'siswa'.$tagihan->siswa->id.'@sppdigital.com',
                    'phone' => '080000000000',
                ]
            ];

           
            $response = \Midtrans\CoreApi::charge($params);

            
            $vaNumber = null;
            if (isset($response->va_numbers[0]->va_number)) {
                $vaNumber = $response->va_numbers[0]->va_number;
            }

            if(!$vaNumber) {
                throw new \Exception("Gagal mendapatkan Virtual Account dari bank.");
            }

          
            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'order_id' => $orderId,
                'gross_amount' => $tagihan->nominal,
                'bank' => strtolower($request->bank),
                'va_number' => $vaNumber,
                'status' => 'menunggu',
                'waktu_transaksi' => now()
            ]);

          
            $tagihan->update(['status' => 'menunggu']);

            DB::commit();
            return back()->with('success', 'Virtual Account ' . strtoupper($request->bank) . ' berhasil digenerate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat generate VA: ' . $e->getMessage());
        }
    }
}
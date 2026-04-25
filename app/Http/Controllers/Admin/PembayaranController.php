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
        // Ubah ini agar dinamis membaca .env (true untuk live, false untuk sandbox)
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); 
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
            
            // ========================================================
            // CARA BARU: Penanganan aman agar VS Code tidak error merah
            // ========================================================
            if (isset($response->va_numbers) && is_array($response->va_numbers) && count($response->va_numbers) > 0) {
                // Paksa ubah menjadi Array agar Intelephense VS Code paham
                $vaData = (array) $response->va_numbers[0];
                
                if (isset($vaData['va_number'])) {
                    $vaNumber = $vaData['va_number'];
                }
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

    // ==========================================
    // FUNGSI BARU: CEK STATUS MANUAL KE MIDTRANS
    // ==========================================
    // ==========================================
    // FUNGSI BARU: CEK STATUS MANUAL KE MIDTRANS
    // ==========================================
    public function cekStatusMidtrans($order_id)
    {
        try {
            // Minta status terbaru langsung ke server Midtrans
            $statusData = \Midtrans\Transaction::status($order_id);

            // --- TRIK AMAN UNTUK VS CODE ---
            // Paksa ubah menjadi Array agar Intelephense tidak merah
            $statusArray = (array) $statusData;
            $transactionStatus = $statusArray['transaction_status'] ?? '';

            // Cari data di database kita
            $pembayaran = Pembayaran::where('order_id', $order_id)->firstOrFail();
            $tagihan = Tagihan::find($pembayaran->tagihan_id);

            // Update status berdasarkan jawaban Midtrans
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                $pembayaran->update(['status' => 'lunas']);
                $tagihan->update(['status' => 'lunas']);
                return back()->with('success', 'Hore! Pembayaran atas nama ' . $tagihan->siswa->nama . ' sudah lunas.');
                
            } elseif ($transactionStatus == 'pending') {
                return back()->with('info', 'Status masih Menunggu Pembayaran dari siswa.');
                
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $pembayaran->update(['status' => 'gagal']);
                $tagihan->update(['status' => 'belum_bayar']);
                return back()->with('error', 'Tagihan ini gagal atau sudah kadaluarsa. Silakan generate VA baru.');
            }

            return back()->with('success', 'Status berhasil dicek.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengecek status ke Midtrans. (Error: ' . $e->getMessage() . ')');
        }
    }
}
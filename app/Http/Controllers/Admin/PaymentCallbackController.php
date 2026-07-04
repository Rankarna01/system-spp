<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function receive(Request $request)
    {
        // 1. Inisialisasi konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            $notification = new \Midtrans\Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id; // Contoh: SPP-1-17135...
            $fraudStatus = $notification->fraud_status;

            // Cari data pembayaran berdasarkan order_id
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'Order ID tidak ditemukan'], 404);
            }

            $tagihan = Tagihan::find($pembayaran->tagihan_id);

            // 2. Logic Perubahan Status
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                // Pembayaran Berhasil
                $pembayaran->update(['status' => 'lunas']);
                $tagihan->update(['status' => 'lunas']);

                // Kirim email kuitansi
                if (!empty($tagihan->siswa->email_orang_tua)) {
                    try {
                        \Illuminate\Support\Facades\Mail::to($tagihan->siswa->email_orang_tua)->send(new \App\Mail\PaymentSuccessMail($tagihan, $pembayaran));
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal mengirim email Lunas: ' . $e->getMessage());
                    }
                }
            } elseif ($transactionStatus == 'pending') {
                // Menunggu Pembayaran
                $pembayaran->update(['status' => 'menunggu']);
                $tagihan->update(['status' => 'menunggu']);
            } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                // Pembayaran Gagal/Kadaluarsa
                $pembayaran->update(['status' => 'gagal']);
                $tagihan->update(['status' => 'belum_bayar']);
            }

            return response()->json(['message' => 'Callback diproses'], 200);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }
}
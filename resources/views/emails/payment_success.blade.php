<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kuitansi Pembayaran SPP</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 20px; color: #374151; line-height: 1.6; }
        .container { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto; }
        .header { background-color: #10b981; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; }
        .success-box { background-color: #d1fae5; border: 1px solid #a7f3d0; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .success-box h2 { font-size: 24px; color: #047857; margin: 10px 0; }
        .success-box p { margin: 0; color: #065f46; font-weight: bold; }
        .details-table { border-collapse: collapse; margin-top: 15px; width: 100%; }
        .details-table th, .details-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .details-table th { color: #6b7280; font-weight: 600; width: 40%; }
        .details-table td { font-weight: bold; }
        .footer { background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bukti Pembayaran SPP</h1>
        </div>
        <div class="content">
            <p>Halo, Orang Tua / Wali dari <strong>{{ $tagihan->siswa->nama }}</strong>,</p>
            <p>Terima kasih! Kami ingin memberitahukan bahwa pembayaran SPP telah berhasil kami terima. Berikut adalah rincian pembayarannya:</p>
            
            <div class="success-box">
                <h2>LUNAS</h2>
                <p>Terima kasih atas pembayaran Anda.</p>
            </div>
            
            <table class="details-table">
                <tr>
                    <th>Nama Siswa</th>
                    <td>{{ $tagihan->siswa->nama }} ({{ $tagihan->siswa->kelas->nama_kelas ?? '' }})</td>
                </tr>
                <tr>
                    <th>Untuk Bulan</th>
                    <td>{{ $tagihan->bulan }} {{ $tagihan->tahun }}</td>
                </tr>
                <tr>
                    <th>Nominal Dibayar</th>
                    <td style="color: #10b981; font-size: 18px;">Rp {{ number_format($pembayaran->gross_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td>VA {{ strtoupper($pembayaran->bank) }}</td>
                </tr>
                <tr>
                    <th>Waktu Lunas</th>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->updated_at)->translatedFormat('d F Y, H:i') }}</td>
                </tr>
            </table>

            <p style="margin-top: 30px;">Email ini adalah bukti pembayaran yang sah yang diterbitkan oleh sistem secara otomatis. Harap simpan email ini sebagai referensi.</p>
            <p>Salam hangat,<br>Tim Administrasi SPP Digital</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem Informasi SPP Digital. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Tagihan SPP</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 20px; color: #374151; line-height: 1.6; }
        .container { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; }
        .va-box { background-color: #eff6ff; border: 1px solid #bfdbfe; padding: 20px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .va-box h2 { font-size: 28px; letter-spacing: 2px; color: #1e3a8a; margin: 10px 0; }
        .va-box p { margin: 0; color: #2563eb; font-weight: bold; font-size: 14px; text-transform: uppercase; }
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
            <h1>Pemberitahuan Tagihan SPP</h1>
        </div>
        <div class="content">
            <p>Halo, Orang Tua / Wali dari <strong>{{ $tagihan->siswa->nama }}</strong>,</p>
            <p>Berikut ini adalah rincian tagihan SPP terbaru berserta nomor Virtual Account (VA) untuk mempermudah proses pembayaran.</p>
            
            <div class="va-box">
                <p>BANK {{ strtoupper($pembayaran->bank) }} VIRTUAL ACCOUNT</p>
                <h2>{{ $pembayaran->va_number }}</h2>
            </div>
            
            <table class="details-table">
                <tr>
                    <th>Nama Siswa</th>
                    <td>{{ $tagihan->siswa->nama }} ({{ $tagihan->siswa->kelas->nama_kelas ?? '' }})</td>
                </tr>
                <tr>
                    <th>Tagihan Bulan</th>
                    <td>{{ $tagihan->bulan }} {{ $tagihan->tahun }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td style="color: #dc2626; font-size: 18px;">Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Batas Pembayaran</th>
                    <td>{{ \Carbon\Carbon::parse($tagihan->jatuh_tempo)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <p style="margin-top: 30px;">Mohon segera lakukan pembayaran sebelum batas waktu yang ditentukan. Abaikan pesan ini jika Anda sudah melakukan pembayaran.</p>
            <p>Terima kasih.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem Informasi SPP Digital. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>

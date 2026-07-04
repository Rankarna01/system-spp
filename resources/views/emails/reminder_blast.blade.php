<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Sekolah</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; padding: 20px; color: #374151; line-height: 1.6; }
        .container { background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 600px; margin: 0 auto; }
        .header { background-color: #f59e0b; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; }
        .message-box { background-color: #fef3c7; border: 1px solid #fde68a; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .message-box p { margin: 0; color: #92400e; font-size: 15px; white-space: pre-line; }
        .footer { background-color: #f9fafb; padding: 15px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $judul }}</h1>
        </div>
        <div class="content">
            <p>Halo, Orang Tua / Wali dari <strong>{{ $nama_siswa }}</strong>,</p>
            <p>Kami dari pihak sekolah ingin menyampaikan pesan pengingat (reminder) sebagai berikut:</p>
            
            <div class="message-box">
                <p>{{ $pesan }}</p>
            </div>

            <p style="margin-top: 30px;">Mohon kerjasamanya. Jika Anda sudah menindaklanjuti pesan ini, silakan abaikan notifikasi ini.</p>
            <p>Terima kasih atas perhatiannya.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sistem Informasi SPP Digital. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>

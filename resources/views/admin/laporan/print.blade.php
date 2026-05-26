<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - {{ $judulLaporan }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; color: #000; font-size: 14px; margin: 0; padding: 20px; }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .logo { width: 80px; height: 80px; object-fit: contain; margin-right: 20px; }
        .header-text { flex: 1; text-align: center; }
        .header-text h1 { margin: 0; font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .header-text h3 { margin: 5px 0 0 0; font-size: 16px; font-weight: normal; }
        .header-text p { margin: 5px 0 0 0; font-size: 12px; }
        .judul-laporan { text-align: center; font-size: 16px; font-weight: bold; text-decoration: underline; margin-bottom: 20px; text-transform: uppercase; }
        table { w-full; border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #000; padding: 8px 12px; text-align: left; font-size: 12px; }
        th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .ttd-container { width: 100%; display: flex; justify-content: flex-end; margin-top: 50px; }
        .ttd-box { text-align: center; width: 250px; }
        @media print {
            @page { margin: 2cm; }
            body { padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="kop-surat">
        @if(isset($gSetting) && $gSetting->logo)
            <img src="{{ asset('storage/' . $gSetting->logo) }}" alt="Logo" class="logo">
        @else
            <div style="width:80px; height:80px; background:#ddd; margin-right:20px; display:flex; align-items:center; justify-content:center; font-size:10px;">LOGO</div>
        @endif
        
        <div class="header-text">
            <h1>{{ $gSetting->nama_sistem ?? 'NAMA SEKOLAH / INSTANSI' }}</h1>
            <h3>{{ $gSetting->slogan_sistem ?? 'Slogan Sekolah' }}</h3>
            <p>
                Alamat: {{ $gSetting->alamat_sekolah ?? 'Jl. Contoh Alamat No. 123' }} <br>
                Telepon: {{ $gSetting->telepon_sekolah ?? '-' }} | Email: {{ $gSetting->email_sekolah ?? '-' }}
            </p>
        </div>
    </div>

    <div class="judul-laporan">
        {{ $judulLaporan }}
        @if($bulanMulai && $bulanSelesai)
            <br><span style="font-size:12px; font-weight:normal; text-decoration:none;">Periode: {{ \Carbon\Carbon::parse($bulanMulai)->translatedFormat('F Y') }} s/d {{ \Carbon\Carbon::parse($bulanSelesai)->translatedFormat('F Y') }}</span>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Siswa</th>
                <th>Kelas / Jurusan</th>
                @if($jenis == 'pemasukan_bulanan')
                    <th>Tanggal Transaksi</th>
                    <th>Metode</th>
                @else
                    <th>Bulan Tagihan</th>
                    <th>Status</th>
                @endif
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSemua = 0; @endphp
            @forelse($data as $key => $item)
                @php 
                    $siswa = $jenis == 'pemasukan_bulanan' ? $item->tagihan->siswa : $item->siswa;
                    $nominal = $jenis == 'pemasukan_bulanan' ? $item->gross_amount : $item->nominal;
                    $totalSemua += $nominal;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $siswa->nama ?? '-' }}</td>
                    <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    
                    @if($jenis == 'pemasukan_bulanan')
                        <td class="text-center">{{ \Carbon\Carbon::parse($item->waktu_transaksi)->translatedFormat('d M Y') }}</td>
                        <td class="text-center">{{ strtoupper($item->bank ?? 'Manual') }}</td>
                    @else
                        <td class="text-center">{{ $item->bulan }} {{ $item->tahun }}</td>
                        <td class="text-center">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                    @endif
                    
                    <td class="text-right">Rp {{ number_format($nominal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data untuk laporan ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br> Mengetahui,</p>
            <br><br><br><br>
            <p style="font-weight: bold; text-decoration: underline;">{{ auth()->user()->name ?? 'Administrator' }}</p>
            <p style="margin-top:-10px;">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</p>
        </div>
    </div>

</body>
</html>
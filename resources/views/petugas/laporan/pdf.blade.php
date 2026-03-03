<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Peminjaman Alat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; margin: 25px; color: #222; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 15px; }
        .header h2 { font-size: 16px; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 1px; }
        .header .subtitle { font-size: 12px; color: #555; }
        .period { text-align: center; margin-bottom: 18px; color: #555; font-size: 11px; }

        .stats-table { width: 100%; margin-bottom: 18px; border-collapse: collapse; }
        .stats-table td { width: 25%; text-align: center; padding: 8px; background: #f8f9fa; border: 1px solid #dee2e6; }
        .stats-table .stat-value { font-size: 18px; font-weight: bold; color: #333; }
        .stats-table .stat-label { font-size: 10px; color: #666; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th, table.data td { border: 1px solid #aaa; padding: 6px 8px; text-align: left; font-size: 10.5px; }
        table.data th { background: #e9ecef; font-weight: 600; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.5px; }
        table.data tr:nth-child(even) { background: #f8f9fa; }

        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: 600; text-transform: uppercase; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-disetujui { background: #d4edda; color: #155724; }
        .badge-ditolak { background: #f8d7da; color: #721c24; }
        .badge-dipinjam { background: #cce5ff; color: #004085; }
        .badge-dikembalikan { background: #d1e7dd; color: #0a3622; }
        .badge-diajukan_kembali { background: #fff3cd; color: #856404; }
        .badge-dibatalkan { background: #e2e3e5; color: #495057; }

        .footer { margin-top: 25px; font-size: 10px; color: #666; }
        .footer-row { width: 100%; }
        .footer-left { float: left; }
        .footer-right { float: right; }

        .ttd { text-align: center; margin-top: 50px; }
        .ttd-line { border-top: 1px solid #333; width: 200px; margin: 0 auto; padding-top: 5px; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h2>Laporan Peminjaman Alat</h2>
        <div class="subtitle">Sistem Manajemen Peminjaman Alat Sekolah</div>
    </div>

    <p class="period">Periode: <b>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</b> s.d <b>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</b></p>

    {{-- Stats --}}
    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </td>
            <td>
                <div class="stat-label">Disetujui</div>
                <div class="stat-value">{{ $stats['disetujui'] }}</div>
            </td>
            <td>
                <div class="stat-label">Ditolak</div>
                <div class="stat-value">{{ $stats['ditolak'] }}</div>
            </td>
            <td>
                <div class="stat-label">Dikembalikan</div>
                <div class="stat-value">{{ $stats['dikembalikan'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th style="width:30px">Jml</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->user->nama ?? '-' }}</td>
                <td>{{ $p->alat->nama_alat ?? '-' }}</td>
                <td>{{ $p->jumlah_pinjam }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; padding: 20px; color: #999;">Tidak ada data peminjaman pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <div class="footer-row">
            <span class="footer-left">Total data: <b>{{ $stats['total'] }}</b> peminjaman</span>
            <span class="footer-right">Dicetak pada: <b>{{ now()->format('d F Y, H:i') }} WIB</b></span>
        </div>
    </div>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p style="margin-top: 50px;"><b>Petugas</b></p>
        <div class="ttd-line">( ........................ )</div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Peminjaman</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; margin: 20px; color: #222; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px double #333; padding-bottom: 15px; }
        .header h2 { font-size: 18px; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 1px; }
        .header .subtitle { font-size: 13px; color: #555; }
        .period { text-align: center; margin-bottom: 20px; color: #555; font-size: 12px; }

        .stats { display: flex; gap: 12px; margin-bottom: 18px; }
        .stat { flex: 1; background: #f8f9fa; padding: 10px; text-align: center; border-radius: 6px; border: 1px solid #dee2e6; }
        .stat b { font-size: 20px; display: block; color: #333; }
        .stat span { font-size: 11px; color: #666; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #aaa; padding: 7px 10px; text-align: left; font-size: 11.5px; }
        th { background: #e9ecef; font-weight: 600; text-transform: uppercase; font-size: 10.5px; letter-spacing: 0.5px; }
        tr:nth-child(even) { background: #f8f9fa; }

        .footer { margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px; color: #666; }
        .ttd { text-align: center; margin-top: 60px; }
        .ttd-line { border-top: 1px solid #333; width: 200px; margin: 0 auto; padding-top: 5px; }

        .badge { padding: 2px 8px; border-radius: 3px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-disetujui { background: #d4edda; color: #155724; }
        .badge-ditolak { background: #f8d7da; color: #721c24; }
        .badge-dipinjam { background: #cce5ff; color: #004085; }
        .badge-dikembalikan { background: #d1e7dd; color: #0a3622; }
        .badge-diajukan_kembali { background: #fff3cd; color: #856404; }
        .badge-dibatalkan { background: #e2e3e5; color: #495057; }

        @media print {
            .no-print { display: none !important; }
            body { margin: 10px; }
        }

        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            justify-content: center;
        }
        .btn {
            padding: 8px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print { background: #343a40; color: white; }
        .btn-print:hover { background: #23272b; }
        .btn-back { background: #6c757d; color: white; }
        .btn-back:hover { background: #5a6268; }
    </style>
</head>
<body>

    {{-- Action Bar (hidden when printing) --}}
    <div class="action-bar no-print">
        <button class="btn btn-print" onclick="window.print()">🖨️ Cetak Langsung</button>
        <button class="btn btn-back" onclick="window.close()">✖ Tutup</button>
    </div>

    {{-- Header --}}
    <div class="header">
        <h2>📋 Laporan Peminjaman Alat</h2>
        <div class="subtitle">Sistem Manajemen Peminjaman Alat Sekolah</div>
    </div>

    <p class="period">Periode: <b>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</b> s.d <b>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</b></p>

    {{-- Stats --}}
    <div class="stats">
        <div class="stat"><span>Total</span><b>{{ $stats['total'] }}</b></div>
        <div class="stat"><span>Disetujui</span><b>{{ $stats['disetujui'] }}</b></div>
        <div class="stat"><span>Ditolak</span><b>{{ $stats['ditolak'] }}</b></div>
        <div class="stat"><span>Dikembalikan</span><b>{{ $stats['dikembalikan'] }}</b></div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th style="width:40px">Jml</th>
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
        <div>Total data: <b>{{ $stats['total'] }}</b> peminjaman</div>
        <div>Dicetak pada: <b>{{ now()->format('d F Y, H:i') }} WIB</b></div>
    </div>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p style="margin-top: 50px;"><b>Petugas</b></p>
        <div class="ttd-line">( ........................ )</div>
    </div>

    <script>
        // Auto-trigger print
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        };
    </script>
</body>
</html>

@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas')
@section('content')
<form class="d-flex gap-2 mb-4 flex-wrap" method="GET">
    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}" style="width: 220px;">
    <select name="tabel" class="form-select" style="width: 150px;">
        <option value="">Semua Tabel</option>
        @foreach(['users','alat','peminjaman','pengembalian','kategori'] as $t)
        <option value="{{ $t }}" {{ request('tabel') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
</form>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Tabel</th><th>Detail</th><th>IP</th></tr></thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td><small>{{ $log->created_at->format('d/m/Y H:i') }}</small></td>
                <td>{{ $log->user->nama ?? 'System' }}</td>
                <td><span class="badge bg-{{ $log->aksi == 'CREATE' ? 'success' : ($log->aksi == 'DELETE' ? 'danger' : ($log->aksi == 'LOGIN' ? 'info' : 'warning')) }}">{{ $log->aksi }}</span></td>
                <td><code>{{ $log->tabel }}</code></td>
                <td><small>{{ Str::limit($log->detail, 60) }}</small></td>
                <td><small class="text-secondary">{{ $log->ip_address ?? '-' }}</small></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada log</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $logs->withQueryString()->links() }}</div>
@endsection

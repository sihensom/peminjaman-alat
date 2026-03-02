<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peminjaman Alat')</title>
    <link rel="icon" type="image/png" href="{{ asset('image.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 280px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --bg-main: #0a0e1a;
            --bg-sidebar: #0f1419;
            --bg-card: #161b26;
            --bg-card-hover: #1c2230;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border: #1e293b;
            --border-light: #334155;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .text-secondary {
            color: #b6c0d3 !important;
        }

        .text-muted {
            color: #8b98ae !important;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0; top: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 1.75rem 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(129, 140, 248, 0.05) 100%);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 10px;
        }

        .sidebar-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(15, 23, 42, 0.7);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.4), 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .sidebar-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-logo h4 {
            font-size: 1.25rem;
            font-weight: 900;
            margin: 0;
            color: white;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .sidebar-subtitle {
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 600;
            padding-left: 62px;
            letter-spacing: 0.02em;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.25rem 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-light);
            border-radius: 4px;
        }

        .nav-section-label {
            padding: 1.25rem 1.5rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #60a5fa;
            background: rgba(96, 165, 250, 0.05);
            margin: 0.5rem 0.75rem 0.5rem;
            border-radius: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0.95rem 1.5rem;
            margin: 3px 12px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            position: relative;
            border: 1px solid transparent;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 0;
            background: linear-gradient(180deg, #818cf8, #6366f1);
            border-radius: 0 4px 4px 0;
            transition: height 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            color: white;
            background: rgba(99, 102, 241, 0.12);
            transform: translateX(4px);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .nav-item.active {
            color: white;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(129, 140, 248, 0.15) 100%);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .nav-item.active::before {
            height: 32px;
        }

        .nav-item i {
            font-size: 1.25rem;
            width: 26px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.9;
        }

        .nav-item.active i {
            opacity: 1;
            color: #a5b4fc;
        }

        .sidebar-footer {
            padding: 1.25rem;
            border-top: 1px solid var(--border);
            background: rgba(99, 102, 241, 0.03);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: var(--bg-card);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: capitalize;
            font-weight: 500;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger);
            padding: 8px 10px;
            border-radius: 8px;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: var(--danger);
            color: var(--danger);
            transform: scale(1.05);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: var(--bg-main);
        }

        .topbar {
            padding: 1.25rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(10, 14, 26, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .page-title {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            background: linear-gradient(135deg, white, var(--text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
        }

        .page-content {
            padding: 2rem;
        }

        /* Enhanced Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            color: var(--text-primary);
        }

        .card h6,
        .card h5,
        .card h4,
        .card .card-title {
            color: var(--text-primary);
        }

        .card .text-secondary {
            color: #b6c0d3 !important;
        }

        .card:hover {
            border-color: var(--border-light);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            border-bottom: 1px solid var(--border);
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, transparent 100%);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.05rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Stat Cards */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.25);
            border-color: var(--border-light);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .stat-icon.blue { 
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.05) 100%);
            color: var(--info);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.2);
        }
        .stat-icon.green { 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0.05) 100%);
            color: var(--success);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.2);
        }
        .stat-icon.yellow { 
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2) 0%, rgba(245, 158, 11, 0.05) 100%);
            color: var(--warning);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.2);
        }
        .stat-icon.red { 
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.05) 100%);
            color: var(--danger);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.2);
        }
        .stat-icon.purple { 
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2) 0%, rgba(139, 92, 246, 0.05) 100%);
            color: #a78bfa;
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.2);
        }

        .stat-details {
            flex: 1;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* Tables */
        .table-responsive {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .table {
            background: transparent;
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 1px solid var(--border-light);
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #cbd5f5;
            padding: 1rem;
            background: rgba(15, 23, 42, 0.65);
        }

        .table tbody td {
            border-bottom: 1px solid var(--border);
            padding: 1rem;
            font-size: 0.9rem;
            vertical-align: middle;
            color: var(--text-primary);
            background: transparent;
        }

        .table tbody tr {
            transition: background 0.2s;
            background: transparent;
        }

        .table tbody tr:hover {
            background: rgba(99, 102, 241, 0.08);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table .text-secondary {
            color: var(--text-secondary) !important;
        }

        .table code {
            background: rgba(148, 163, 184, 0.15);
            color: #e2e8f0;
            padding: 0.2rem 0.4rem;
            border-radius: 6px;
        }

        .table-borderless > :not(caption) > * > * {
            border-bottom: none;
        }

        /* Badges */
        .badge-status {
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.02em;
        }

        .badge-pending { 
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .badge-disetujui { 
            background: rgba(59, 130, 246, 0.15);
            color: var(--info);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        .badge-dipinjam { 
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }
        .badge-ditolak { 
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-dikembalikan { 
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
        .badge-diajukan_kembali { 
            background: rgba(251, 146, 60, 0.15);
            color: #fb923c;
            border: 1px solid rgba(251, 146, 60, 0.3);
        }
        .badge-dibatalkan { 
            background: rgba(100, 116, 139, 0.15);
            color: #94a3b8;
            border: 1px solid rgba(100, 116, 139, 0.3);
        }

        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        }

        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.85rem;
        }

        /* Forms */
        .form-control, .form-select {
            background: var(--bg-main);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            background: var(--bg-card);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            color: var(--text-primary);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Pagination */
        .pagination {
            --bs-pagination-bg: var(--bg-card);
            --bs-pagination-border-color: var(--border);
            --bs-pagination-color: var(--text-primary);
            --bs-pagination-hover-bg: var(--primary);
            --bs-pagination-hover-border-color: var(--primary);
            --bs-pagination-hover-color: white;
            --bs-pagination-active-bg: var(--primary);
            --bs-pagination-active-border-color: var(--primary);
            --bs-pagination-focus-bg: rgba(99, 102, 241, 0.25);
            --bs-pagination-focus-color: white;
            --bs-pagination-disabled-bg: rgba(15, 23, 42, 0.6);
            --bs-pagination-disabled-color: var(--text-muted);
            gap: 6px;
        }

        .page-link {
            border-radius: 8px !important;
            margin: 0;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid var(--border);
            color: var(--text-primary);
            font-weight: 600;
            padding: 0.45rem 0.75rem;
        }

        .page-link:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.6);
            color: white;
        }

        .page-link:focus {
            background: rgba(99, 102, 241, 0.25);
            color: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 6px 14px rgba(99, 102, 241, 0.35);
        }

        .page-item.disabled .page-link {
            background: rgba(15, 23, 42, 0.6);
            border-color: var(--border);
            color: var(--text-muted);
            opacity: 0.7;
        }

        nav[aria-label="Pagination Navigation"] {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        nav[aria-label="Pagination Navigation"] .text-gray-700,
        nav[aria-label="Pagination Navigation"] .text-gray-600 {
            color: var(--text-secondary);
        }

        nav[aria-label="Pagination Navigation"] .bg-white {
            background: rgba(15, 23, 42, 0.8);
        }

        nav[aria-label="Pagination Navigation"] .border-gray-300 {
            border-color: var(--border);
        }

        nav[aria-label="Pagination Navigation"] .text-gray-800,
        nav[aria-label="Pagination Navigation"] .text-gray-500,
        nav[aria-label="Pagination Navigation"] .text-gray-700 {
            color: var(--text-primary);
        }

        nav[aria-label="Pagination Navigation"] .shadow-sm {
            box-shadow: none;
        }

        nav[aria-label="Pagination Navigation"] a:hover {
            background: rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.6);
            color: white;
        }

        nav[aria-label="Pagination Navigation"] .dark\:bg-gray-700,
        nav[aria-label="Pagination Navigation"] .dark\:bg-gray-800 {
            background: rgba(15, 23, 42, 0.8);
        }

        nav[aria-label="Pagination Navigation"] .dark\:text-gray-300,
        nav[aria-label="Pagination Navigation"] .dark\:text-gray-400 {
            color: var(--text-secondary);
        }

        nav[aria-label="Pagination Navigation"] .dark\:border-gray-600 {
            border-color: var(--border);
        }

        nav[aria-label="Pagination Navigation"] .bg-gray-200 {
            background: rgba(99, 102, 241, 0.3);
            color: white;
            border-color: rgba(99, 102, 241, 0.6);
        }

        nav[aria-label="Pagination Navigation"] .hidden,
        nav[aria-label="Pagination Navigation"] .sm\:hidden {
            display: none;
        }

        nav[aria-label="Pagination Navigation"] .sm\:flex {
            display: flex;
        }

        nav[aria-label="Pagination Navigation"] .sm\:flex-1 {
            flex: 1;
        }

        nav[aria-label="Pagination Navigation"] .sm\:items-center {
            align-items: center;
        }

        nav[aria-label="Pagination Navigation"] .sm\:justify-between {
            justify-content: space-between;
        }

        nav[aria-label="Pagination Navigation"] .sm\:gap-2 {
            gap: 0.5rem;
        }

        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 2000;
            pointer-events: none;
        }

        .app-toast {
            min-width: 280px;
            max-width: 420px;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: var(--text-primary);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.35);
            transform: translateY(-6px);
            opacity: 0;
            transition: all 0.25s ease;
            pointer-events: auto;
        }

        .app-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .app-toast.success {
            border-color: rgba(16, 185, 129, 0.5);
        }

        .app-toast.error {
            border-color: rgba(239, 68, 68, 0.5);
        }

        .app-toast .toast-icon {
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .app-toast .toast-body {
            flex: 1;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .app-toast .toast-close {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1;
            padding: 2px;
        }

        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(3, 7, 18, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2100;
            padding: 16px;
        }

        .confirm-overlay.show {
            display: flex;
        }

        .confirm-modal {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            padding: 20px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.45);
        }

        .confirm-title {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .confirm-text {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .confirm-actions {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            justify-content: flex-end;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.3;
        }

        /* Mobile Responsive */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0,0,0,0.5);
            }
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .page-content {
                padding: 1rem;
            }
            .topbar {
                padding: 0.875rem 1rem;
            }
            .page-title {
                font-size: 1.15rem;
            }
            .stat-card {
                padding: 1rem;
            }
            .stat-value {
                font-size: 1.35rem;
            }
            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
                border-radius: 10px;
            }
            .card-header {
                padding: 1rem;
                font-size: 0.95rem;
            }
            .card-body {
                padding: 1rem;
            }
            .table thead th {
                font-size: 0.7rem;
                padding: 0.75rem 0.5rem;
                white-space: nowrap;
            }
            .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.82rem;
            }
            .btn {
                padding: 0.5rem 0.85rem;
                font-size: 0.85rem;
            }
            .btn-sm {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }
            .form-control, .form-select {
                padding: 0.625rem 0.85rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .page-content {
                padding: 0.75rem;
            }
            .stat-card {
                padding: 0.85rem;
                gap: 0.75rem;
            }
            .stat-value {
                font-size: 1.15rem;
            }
            .stat-label {
                font-size: 0.75rem;
            }
            .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 1rem;
            }
            .row.g-3 > [class*="col-"] {
                padding-left: 6px;
                padding-right: 6px;
            }
            .card {
                border-radius: 12px;
            }
            /* Force table to scroll horizontally */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table {
                min-width: 500px;
            }
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: var(--primary-light);
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .mobile-toggle:hover {
            background: rgba(99, 102, 241, 0.25);
        }

        @media (max-width: 992px) {
            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <div class="sidebar-logo-icon">
                    <img src="{{ asset('image.png') }}" alt="Logo">
                </div>
                <h4>Peminjaman Alat</h4>
            </div>
            <div class="sidebar-subtitle">Sistem Manajemen</div>
        </div>

        <nav class="sidebar-nav">
            @auth
                @if(auth()->user()->isAdmin())
                    <div class="nav-section-label">Menu Admin</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Kelola User
                    </a>
                    <a href="{{ route('admin.kategori.index') }}" class="nav-item {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill"></i> Kategori
                    </a>
                    <a href="{{ route('admin.alat.index') }}" class="nav-item {{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill"></i> Kelola Alat
                    </a>
                    <a href="{{ route('admin.peminjaman.index') }}" class="nav-item {{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check-fill"></i> Peminjaman
                    </a>
                    <a href="{{ route('admin.pengembalian.index') }}" class="nav-item {{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-return-left"></i> Pengembalian
                    </a>
                    <a href="{{ route('admin.log.index') }}" class="nav-item {{ request()->routeIs('admin.log.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i> Log Aktivitas
                    </a>

                @elseif(auth()->user()->isPetugas())
                    <div class="nav-section-label">Menu Petugas</div>
                    <a href="{{ route('petugas.dashboard') }}" class="nav-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('petugas.approval.index') }}" class="nav-item {{ request()->routeIs('petugas.approval.*') ? 'active' : '' }}">
                        <i class="bi bi-check-circle-fill"></i> Persetujuan
                    </a>
                    <a href="{{ route('petugas.monitor.index') }}" class="nav-item {{ request()->routeIs('petugas.monitor.*') ? 'active' : '' }}">
                        <i class="bi bi-eye-fill"></i> Monitor
                    </a>
                    <a href="{{ route('petugas.laporan.index') }}" class="nav-item {{ request()->routeIs('petugas.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-printer-fill"></i> Laporan
                    </a>

                @elseif(auth()->user()->isPeminjam())
                    <div class="nav-section-label">Menu Peminjam</div>
                    <a href="{{ route('peminjam.dashboard') }}" class="nav-item {{ request()->routeIs('peminjam.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                    <a href="{{ route('peminjam.alat.index') }}" class="nav-item {{ request()->routeIs('peminjam.alat.*') ? 'active' : '' }}">
                        <i class="bi bi-search"></i> Lihat Alat
                    </a>
                    <a href="{{ route('peminjam.pinjam.index') }}" class="nav-item {{ request()->routeIs('peminjam.pinjam.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard2-check-fill"></i> Peminjaman Saya
                    </a>
                    <a href="{{ route('peminjam.pengembalian.index') }}" class="nav-item {{ request()->routeIs('peminjam.pengembalian.*') ? 'active' : '' }}">
                        <i class="bi bi-arrow-return-left"></i> Riwayat Pengembalian
                    </a>
                @endif
            @endauth
        </nav>

        @auth
        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </div>
                <div class="user-info">
                    <p class="user-name">{{ auth()->user()->nama }}</p>
                    <small class="user-role">{{ auth()->user()->role }}</small>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div>
                @yield('topbar-actions')
            </div>
        </div>

        <div class="page-content fade-in">
            <div class="toast-container" id="toastContainer">
                @if(session('success'))
                    <div class="app-toast success" data-timeout="4000">
                        <i class="bi bi-check-circle-fill toast-icon"></i>
                        <div class="toast-body">{{ session('success') }}</div>
                        <button class="toast-close" type="button" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="app-toast error" data-timeout="5000">
                        <i class="bi bi-exclamation-circle-fill toast-icon"></i>
                        <div class="toast-body">{{ session('error') }}</div>
                        <button class="toast-close" type="button" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="app-toast error" data-timeout="6000">
                        <i class="bi bi-exclamation-circle-fill toast-icon"></i>
                        <div class="toast-body">{{ $errors->first() }}</div>
                        <button class="toast-close" type="button" aria-label="Close">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                @endif
            </div>

            @yield('content')
        </div>
    </main>

    <div class="confirm-overlay" id="confirmOverlay" aria-hidden="true">
        <div class="confirm-modal">
            <div class="confirm-title" id="confirmTitle">Konfirmasi</div>
            <div class="confirm-text" id="confirmText">Apakah Anda yakin?</div>
            <div class="confirm-actions">
                <button class="btn btn-outline-secondary" type="button" id="confirmCancel">Batal</button>
                <button class="btn btn-danger" type="button" id="confirmApprove">Ya, Lanjut</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 992) closeSidebar();
            });
        });

        const toastContainer = document.getElementById('toastContainer');
        if (toastContainer) {
            toastContainer.querySelectorAll('.app-toast').forEach((toast) => {
                const timeout = Number(toast.dataset.timeout || 4000);
                requestAnimationFrame(() => toast.classList.add('show'));
                const closeBtn = toast.querySelector('.toast-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => toast.remove());
                }
                if (timeout > 0) {
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 300);
                    }, timeout);
                }
            });
        }

        let pendingConfirmForm = null;
        const confirmOverlay = document.getElementById('confirmOverlay');
        const confirmApprove = document.getElementById('confirmApprove');
        const confirmCancel = document.getElementById('confirmCancel');
        const confirmText = document.getElementById('confirmText');

        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingConfirmForm = form;
                if (confirmText) {
                    confirmText.textContent = form.dataset.confirm || 'Apakah Anda yakin?';
                }
                if (confirmOverlay) {
                    confirmOverlay.classList.add('show');
                    confirmOverlay.setAttribute('aria-hidden', 'false');
                }
            });
        });

        if (confirmCancel) {
            confirmCancel.addEventListener('click', () => {
                pendingConfirmForm = null;
                if (confirmOverlay) {
                    confirmOverlay.classList.remove('show');
                    confirmOverlay.setAttribute('aria-hidden', 'true');
                }
            });
        }

        if (confirmApprove) {
            confirmApprove.addEventListener('click', () => {
                if (pendingConfirmForm) {
                    pendingConfirmForm.submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

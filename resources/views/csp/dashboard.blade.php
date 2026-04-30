<!DOCTYPE html>
<html>
<head>
    <title>CSP Dashboard</title>

    <style nonce="{{ csp_nonce() }}">
        /* ===== GLOBAL ===== */
        body {
            font-family: 'Inter', Arial, sans-serif;
            background: linear-gradient(135deg, #eef2f7, #f8fafc);
            margin: 0;
            padding: 30px;
            color: #1f2937;
        }

        .box {
            max-width: 1200px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
        }

        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* ===== SUCCESS MESSAGE ===== */
        .success-msg {
            background: #ecfdf5;
            color: #16a34a;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        /* ===== FILTERS ===== */
        .filters {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .filters input {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            min-width: 200px;
            transition: 0.2s;
        }

        .filters input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 10px 14px;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn:hover {
            background: #1f2937;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #ef4444;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 12px;
            overflow: hidden;
        }

        thead {
            background: #111827;
            color: white;
        }

        th, td {
            padding: 12px 14px;
            text-align: left;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        td {
            color: #374151;
        }

        tbody tr td[colspan] {
            text-align: center;
            padding: 20px;
            color: #6b7280;
        }

        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination li {
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            text-decoration: none;
            color: #374151;
            background: #fff;
            transition: 0.2s;
        }

        .pagination li.active span {
            background: #111827;
            color: #fff;
            border-color: #111827;
        }

        .pagination li a:hover {
            background: #f3f4f6;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
            }

            .filters input {
                width: 100%;
            }

            th, td {
                font-size: 12px;
                padding: 10px;
            }

            .box {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="box">

    <h2>CSP Violation Dashboard</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success-msg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="filters">
        <input type="text" name="ip" placeholder="Filter by IP">
        <input type="text" name="directive" placeholder="Directive">
        <button class="btn">Filter</button>
    </form>

    {{-- Clear Logs --}}
    <form method="POST" action="/csp-clear">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" style="margin-top:10px;">
            Clear Logs
        </button>
    </form>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>IP</th>
                <th>Blocked URI</th>
                <th>Directive</th>
                <th>Document</th>
                <th>Time</th>
            </tr>
        </thead>

        <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->blocked_uri }}</td>
                <td>{{ $log->violated_directive }}</td>
                <td>{{ $log->document_uri }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No CSP logs found</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div>
        {{ $logs->links() }}
    </div>

</div>

</body>
</html>
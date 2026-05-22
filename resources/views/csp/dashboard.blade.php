<!DOCTYPE html>
<html>
<head>
    <title>CSP Dashboard - Security Monitor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style nonce="{{ csp_nonce() }}">
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        /* Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 16px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header h1 small {
            font-size: 14px;
            opacity: 0.7;
            margin-left: 10px;
        }

        .badge {
            background: #e74c3c;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
        }

        .stat-card .trend {
            font-size: 12px;
            margin-top: 8px;
        }

        /* Alert Banner */
        .alert-banner {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            justify-content: space-between;
        }

        .alert-banner.show {
            display: flex;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-outline:hover {
            background: #f3f4f6;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Filters */
        .filters-bar {
            padding: 18px 22px;
            background: #f9fafb;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            border-bottom: 1px solid #e5e7eb;
        }

        .filters-bar input, .filters-bar select {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        .filters-bar input:focus, .filters-bar select:focus {
            border-color: #3b82f6;
            ring: 2px solid rgba(59,130,246,0.2);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 16px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            border-bottom: 1px solid #f0f0f0;
            color: #4b5563;
        }

        tr:hover td {
            background: #f9fafb;
        }

        .unread-row td {
            background: #eff6ff;
            font-weight: 500;
        }

        /* Checkbox */
        .checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Domain Tags */
        .domain-tag {
            display: inline-block;
            padding: 4px 8px;
            background: #e5e7eb;
            border-radius: 6px;
            font-size: 11px;
            font-family: monospace;
        }

        .domain-blocked {
            background: #fee2e2;
            color: #dc2626;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            padding: 18px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 22px;
        }

        .modal-body p {
            margin-bottom: 12px;
            line-height: 1.5;
        }

        .modal-body strong {
            color: #1f2937;
            width: 120px;
            display: inline-block;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6b7280;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: flex-end;
            padding: 18px 22px;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            font-size: 13px;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
        }

        /* Success/Error Messages */
        .flash-message {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 20px;
            border-radius: 10px;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }

        .flash-success {
            background: #10b981;
            color: white;
        }

        .flash-error {
            background: #ef4444;
            color: white;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 12px;
            }
            
            .header {
                flex-direction: column;
                text-align: center;
            }
            
            th, td {
                padding: 10px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    
    <!-- Header -->
    <div class="header">
        <h1>
            🛡️ CSP Violation Dashboard
            <small>Real-time Security Monitoring</small>
        </h1>
        <div>
            <span class="badge" id="unreadBadge">{{ $stats['unread_count'] ?? $logs->where('is_read', false)->count() }} Unread</span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="flash-message flash-success" id="flashMessage">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="flash-message flash-error" id="flashMessage">
            {{ session('error') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Total Violations</div>
            <div class="value">{{ number_format($stats['total_violations']) }}</div>
            <div class="trend">All time</div>
        </div>
        <div class="stat-card">
            <div class="label">Today</div>
            <div class="value">{{ number_format($stats['today_violations']) }}</div>
            <div class="trend">Last 24 hours</div>
        </div>
        <div class="stat-card">
            <div class="label">Unique IPs</div>
            <div class="value">{{ number_format($stats['unique_ips']) }}</div>
            <div class="trend">Different sources</div>
        </div>
        <div class="stat-card">
            <div class="label">Blocked Domains</div>
            <div class="value">{{ number_format($stats['total_blocked_domains']) }}</div>
            <div class="trend">Tracked domains</div>
        </div>
    </div>

    <!-- Violations by Directive -->
    @if(isset($stats['violations_by_directive']) && count($stats['violations_by_directive']) > 0)
    <div class="card">
        <div class="card-header">
            <h2>📊 Top Violated Directives</h2>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>Directive</th><th>Count</th><th>%</th></tr>
                </thead>
                <tbody>
                    @php $total = $stats['total_violations']; @endphp
                    @foreach($stats['violations_by_directive'] as $directive)
                    <tr>
                        <td>{{ $directive->violated_directive ?? 'N/A' }}</td>
                        <td>{{ number_format($directive->total) }}</td>
                        <td>{{ $total > 0 ? round(($directive->total / $total) * 100, 1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Blocked Domains Management -->
    <div class="card">
        <div class="card-header">
            <h2>🚫 Blocked Domains Management</h2>
            <button class="btn btn-primary btn-sm" onclick="showAddDomainModal()">+ Add Domain</button>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr><th>Domain</th><th>Action</th><th>Hits</th><th>Reason</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($blockedDomains as $domain)
                    <tr>
                        <td><span class="domain-tag {{ $domain->action === 'block' ? 'domain-blocked' : '' }}">{{ $domain->domain }}</span></td>
                        <td>{{ ucfirst($domain->action) }}</td>
                        <td>{{ number_format($domain->hit_count) }}</td>
                        <td>{{ $domain->reason ?? '-' }}</td>
                        <td>
                            <form method="POST" action="{{ url('/csp-block-domain/' . $domain->id) }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Remove this domain?')">Remove</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center">No domains configured</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Violations Logs -->
    <div class="card">
        <div class="card-header">
            <h2>📋 Violation Logs</h2>
            <div style="display:flex; gap:8px;">
                <button class="btn btn-outline btn-sm" onclick="exportLogs('csv')">📄 Export CSV</button>
                <button class="btn btn-outline btn-sm" onclick="exportLogs('json')">📋 Export JSON</button>
                <button class="btn btn-secondary btn-sm" onclick="markSelectedRead()">✓ Mark Read</button>
                <button class="btn btn-danger btn-sm" onclick="bulkDelete()">🗑️ Bulk Delete</button>
            </div>
        </div>
        
        <!-- Filters -->
        <form method="GET" class="filters-bar">
            <input type="text" name="ip" placeholder="Filter by IP" value="{{ request('ip') }}">
            <input type="text" name="directive" placeholder="Violated Directive" value="{{ request('directive') }}">
            <input type="text" name="domain_filter" placeholder="Blocked Domain" value="{{ request('domain_filter') }}">
            <input type="date" name="date_from" placeholder="From Date" value="{{ request('date_from') }}">
            <input type="date" name="date_to" placeholder="To Date" value="{{ request('date_to') }}">
            <button class="btn btn-primary btn-sm">🔍 Filter</button>
            <a href="{{ url('/csp-dashboard') }}" class="btn btn-outline btn-sm">↺ Reset</a>
        </form>

        <!-- Logs Table -->
        <div class="table-responsive">
            <form id="bulkForm" method="POST">
                @csrf
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" class="checkbox"></th>
                            <th>ID</th>
                            <th>IP</th>
                            <th>Blocked URI</th>
                            <th>Directive</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr class="{{ !$log->is_read ? 'unread-row' : '' }}">
                            <td><input type="checkbox" name="ids[]" value="{{ $log->id }}" class="log-checkbox"></td>
                            <td>#{{ $log->id }}</td>
                            <td>
                                {{ $log->ip_address }}
                                @if($log->user_agent)
                                    <div style="font-size:10px; color:#999;">{{ Str::limit($log->user_agent, 30) }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="domain-tag" title="{{ $log->blocked_uri }}">
                                    {{ Str::limit($log->blocked_uri, 40) }}
                                </span>
                            </td>
                            <td>{{ $log->violated_directive ?? 'N/A' }}</td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>
                                <button type="button" class="btn btn-outline btn-sm" onclick="viewDetails({{ $log->id }})">👁️ View</button>
                                <form method="POST" action="{{ url('/csp-log/' . $log->id) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this log?')">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" style="text-align:center; padding:40px;">✨ No CSP violation logs found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<!-- Add Domain Modal -->
<div id="addDomainModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>➕ Add Blocked Domain</h3>
            <button class="close-modal" onclick="closeAddDomainModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ url('/csp-block-domain') }}">
                @csrf
                <p><strong>Domain:</strong><br>
                <input type="text" name="domain" placeholder="example.com" style="width:100%; padding:8px; margin-top:5px;" required></p>
                
                <p><strong>Action:</strong><br>
                <select name="action" style="width:100%; padding:8px; margin-top:5px;">
                    <option value="block">🚫 Block</option>
                    <option value="allow">✅ Allow</option>
                </select></p>
                
                <p><strong>Reason (optional):</strong><br>
                <textarea name="reason" rows="3" style="width:100%; padding:8px; margin-top:5px;"></textarea></p>
                
                <button type="submit" class="btn btn-primary" style="width:100%;">Add Domain</button>
            </form>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>📄 Violation Details</h3>
            <button class="close-modal" onclick="closeDetailsModal()">&times;</button>
        </div>
        <div class="modal-body" id="detailsContent">
            Loading...
        </div>
    </div>
</div>

<script nonce="{{ csp_nonce() }}">
    // Auto-hide flash message
    setTimeout(() => {
        const flash = document.getElementById('flashMessage');
        if (flash) flash.style.display = 'none';
    }, 3000);

    // Select all checkboxes
    document.getElementById('selectAll')?.addEventListener('change', function(e) {
        document.querySelectorAll('.log-checkbox').forEach(cb => cb.checked = e.target.checked);
    });

    // Export logs
    function exportLogs(format) {
        let url = '/csp-export?format=' + format;
        const params = new URLSearchParams(window.location.search);
        params.forEach((value, key) => {
            if (key !== 'page') url += '&' + key + '=' + encodeURIComponent(value);
        });
        window.location.href = url;
    }

    // Bulk delete
    function bulkDelete() {
        const selected = document.querySelectorAll('.log-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select logs to delete');
            return;
        }
        if (confirm('Delete ' + selected.length + ' log(s)?')) {
            const form = document.getElementById('bulkForm');
            form.action = '/csp-bulk-delete';
            form.method = 'POST';
            form.submit();
        }
    }

    // Mark selected as read
    function markSelectedRead() {
        const selected = document.querySelectorAll('.log-checkbox:checked');
        if (selected.length === 0) {
            alert('Please select logs to mark as read');
            return;
        }
        const form = document.getElementById('bulkForm');
        form.action = '/csp-mark-read';
        form.method = 'POST';
        form.submit();
    }

    // View details modal
    async function viewDetails(id) {
        const modal = document.getElementById('detailsModal');
        const content = document.getElementById('detailsContent');
        modal.classList.add('show');
        content.innerHTML = 'Loading...';
        
        try {
            const response = await fetch('/csp-log/' + id);
            const data = await response.json();
            content.innerHTML = `
                <p><strong>ID:</strong> ${data.id}</p>
                <p><strong>Document URI:</strong><br>${data.document_uri || 'N/A'}</p>
                <p><strong>Blocked URI:</strong><br>${data.blocked_uri || 'N/A'}</p>
                <p><strong>Violated Directive:</strong> ${data.violated_directive || 'N/A'}</p>
                <p><strong>IP Address:</strong> ${data.ip_address || 'N/A'}</p>
                <p><strong>User Agent:</strong><br><small>${data.user_agent || 'N/A'}</small></p>
                <p><strong>Referrer:</strong> ${data.referrer || 'N/A'}</p>
                <p><strong>Time:</strong> ${data.created_at}</p>
            `;
        } catch(e) {
            content.innerHTML = '<p style="color:red;">Error loading details</p>';
        }
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.remove('show');
    }

    function showAddDomainModal() {
        document.getElementById('addDomainModal').classList.add('show');
    }

    function closeAddDomainModal() {
        document.getElementById('addDomainModal').classList.remove('show');
    }

    // Close modals on outside click
    window.onclick = function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.classList.remove('show');
        }
    }

    // Auto-refresh stats every 30 seconds
    setInterval(async () => {
        try {
            const response = await fetch('/csp-stats');
            const stats = await response.json();
            document.getElementById('unreadBadge').innerText = stats.unread_count + ' Unread';
        } catch(e) {}
    }, 30000);
</script>

</body>
</html>
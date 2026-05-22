<!DOCTYPE html>
<html>
<head>
    <title>CSP Protection System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            background: #1a1a2e;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .card-header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .card-header p {
            margin-top: 8px;
            opacity: 0.8;
            font-size: 14px;
        }

        .card-body {
            padding: 30px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .message {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .button-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
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
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .test-area {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .test-area h3 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 12px;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Security Headers Active</h1>
                <p>Content Security Policy is protecting this application</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="message">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="stats">
                    <div class="stat">
                        <div class="stat-number">{{ number_format($stats['total'] ?? 0) }}</div>
                        <div class="stat-label">Total Violations</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">{{ number_format($stats['today'] ?? 0) }}</div>
                        <div class="stat-label">Today</div>
                    </div>
                    <div class="stat">
                        <div class="stat-number">{{ number_format($stats['blocked_domains'] ?? 0) }}</div>
                        <div class="stat-label">Blocked Domains</div>
                    </div>
                </div>

                <div class="button-group">
                    <a href="/csp-dashboard" class="btn btn-primary">View Dashboard</a>
                    
                    <form method="POST" action="/csp-clear" style="display: inline;" 
                          onsubmit="return confirm('Clear all violation logs? This cannot be undone.');">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger">Clear All Logs</button>
                    </form>
                </div>

                <hr>

                <div class="test-area">
                    <h3>Test CSP Protection</h3>
                    <button onclick="testCSP()" class="btn btn-secondary">Trigger Test Violation</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testCSP() {
            var script = document.createElement('script');
            script.src = 'https://test-blocked-domain.example.com/evil.js';
            document.body.appendChild(script);
            alert('Test violation sent! Check the dashboard.');
        }
    </script>
</body>
</html>
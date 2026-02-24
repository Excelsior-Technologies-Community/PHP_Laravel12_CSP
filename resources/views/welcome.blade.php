<!DOCTYPE html>
<html>

<head>
    <title>Laravel 12 CSP</title>


    <!-- Inline style with nonce -->
    <style nonce="{{ csp_nonce() }}">
        body {
            font-family: 'Roboto', sans-serif;
            background: #f8fafc;
            padding: 20px;
        }

        h1 {
            color: #111827;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 40px auto;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Laravel 12 CSP Working</h1>
    </div>

    <script nonce="{{ csp_nonce() }}">
        console.log("CSP Script Allowed");
    </script>
</body>

</html>
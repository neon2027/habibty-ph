<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Habibty Legazpi PH Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        h1 { color: #be185d; font-size: 22px; margin-bottom: 4px; }
        .meta { color: #6b7280; margin-bottom: 18px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #fce7f3; color: #831843; text-align: left; }
        th, td { border: 1px solid #fbcfe8; padding: 8px; }
        .total { margin: 14px 0; font-weight: bold; }
        .money { text-align: right; }
    </style>
</head>
<body>
    <h1>Habibty Legazpi PH Sales Report</h1>
    <div class="meta">Generated {{ $generatedAt->format('F d, Y h:i A') }}</div>
    <div class="total">Registered customers: {{ number_format($totalCustomers) }}</div>

    <table>
        <thead>
            <tr>
                <th>Period</th>
                <th>Date range</th>
                <th>Customers</th>
                <th>Orders</th>
                <th>Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $row['label'] }}</td>
                    <td>{{ $row['starts_at']->format('Y-m-d') }} to {{ $row['ends_at']->format('Y-m-d') }}</td>
                    <td>{{ number_format($row['customers']) }}</td>
                    <td>{{ number_format($row['orders']) }}</td>
                    <td class="money">PHP {{ number_format($row['sales'] / 100, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

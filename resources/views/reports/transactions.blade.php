<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Transaction Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h1>Transaction Report from {{ $from ?? '...' }} to {{ $to ?? '...' }}</h1>

    <p>Total Income: {{ number_format($totalIncome, 2) }}</p>
    <p>Total Expenses: {{ number_format($totalExpenses, 2) }}</p>

    <h2>Transactions</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Comment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $t)
                <tr>
                    <td>{{ $t->transaction_date }}</td>
                    <td>{{ $t->category->name ?? 'No Category' }}</td>
                    <td>{{ number_format($t->amount, 2) }}</td>
                    <td>{{ $t->comment ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

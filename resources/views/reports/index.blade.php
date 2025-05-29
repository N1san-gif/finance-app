@extends('layouts.app')
@section('content')
@php
    $transactions = $transactions ?? collect();
    $totalIncome = $totalIncome ?? 0;
    $totalExpenses = $totalExpenses ?? 0;
    $byCategory = $byCategory ?? [];
@endphp
<div class="container">
    <h2 class="mb-4">Transaction Report</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('reports.generate') }}" class="row g-3 mb-4">
        @csrf
        <div class="col-md-4">
            <label for="from" class="form-label">Start Date</label>
            <input type="date" name="from" id="from" class="form-control" required value="{{ old('from', request('from')) }}">
        </div>

        <div class="col-md-4">
            <label for="to" class="form-label">End Date</label>
            <input type="date" name="to" id="to" class="form-control" required value="{{ old('to', request('to')) }}">
        </div>

        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary w-100 mb-2">Generate Report</button>
            <button type="submit" formaction="{{ route('reports.generatePdf') }}" formmethod="POST" class="btn btn-success w-100 mb-2">
                Download PDF
            </button>
            <button type="submit" formaction="{{ route('reports.sendEmail') }}" formmethod="POST" class="btn btn-info w-100">
                Send by Email
            </button>
        </div>
    </form>

    @if($transactions->isNotEmpty())
        <hr>
        <h4>Summary</h4>
        <p>Income: <strong>{{ $totalIncome }}</strong></p>
        <p>Expenses: <strong>{{ $totalExpenses }}</strong></p>

        <h4>By Category</h4>
        <ul>
            @foreach($byCategory as $category => $amount)
                <li>{{ $category }}: {{ $amount }}</li>
            @endforeach
        </ul>

        <h4>Transaction List</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>{{ $transaction->category->name ?? 'No Category' }}</td>
                    <td>{{ $transaction->amount }}</td>
                    <td>{{ $transaction->status->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @elseif(request()->filled('from') && request()->filled('to'))
        <p class="text-muted">No transactions for the selected period.</p>
    @endif
</div>
@endsection

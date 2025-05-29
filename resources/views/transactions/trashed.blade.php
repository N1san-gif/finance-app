@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Navigation -->
    <nav class="mb-4">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transactions.index') }}">Transactions</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('transactions.trashed') }}">Trash (Deleted)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('categories.index') }}">Categories</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('categories.trashed') }}">Category Trash</a>
            </li>
        </ul>
    </nav>

    <h1 class="mb-4">Deleted Transactions (Trash)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($transactions->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th style="min-width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->category->name ?? '—' }}</td>
                    <td>{{ number_format($transaction->amount, 2, ',', ' ') }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->transaction_date->format('Y-m-d') }}</td>
                    <td>
                        <form action="{{ route('transactions.restore', $transaction->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-sm btn-success" type="submit" title="Restore">Restore</button>
                        </form>

                        <form action="{{ route('transactions.forceDelete', $transaction->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete permanently?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit" title="Delete permanently">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $transactions->links() }}

    @else
        <p>The trash is empty.</p>
    @endif

    <a href="{{ route('transactions.index') }}" class="btn btn-primary mt-3">Back to Transactions</a>
</div>
@endsection

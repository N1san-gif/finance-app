@extends('layouts.app')

@section('title', 'Transaction List')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Transaction List</h1>
        <a href="{{ route('transactions.trashed') }}" class="btn btn-warning">Trash
        </a>
        <a href="{{ route('transactions.create') }}" class="btn btn-success">
            Add Transaction
        </a>
    </div>

    @if($transactions->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_date }}</td>
                        <td>{{ $transaction->category->name ?? '—' }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->status->name ?? '—' }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $transactions->links() }} {{-- Pagination --}}
    @else
        <p>No transactions to display.</p>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>View Transaction</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $transaction->id }}</p>
            <p><strong>Category:</strong> {{ $transaction->category->name ?? '—' }}</p>
            <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
            <p><strong>Description:</strong> {{ $transaction->description }}</p>
            <p><strong>Date:</strong> {{ $transaction->transaction_date->format('Y-m-d') }}</p>
        </div>
    </div>

    <a href="{{ route('transactions.index') }}" class="btn btn-primary mt-3">Back to list</a>
</div>
@endsection

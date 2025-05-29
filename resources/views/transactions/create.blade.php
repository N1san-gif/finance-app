@extends('layouts.app')

@section('title', 'Add Transaction')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Add Transaction</h2>

    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf {{-- Required for protection against 419 error --}}

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select name="category_id" id="category_id" class="form-select" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->type }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status_id" class="form-label">Status</label>
            <select name="status_id" id="status_id" class="form-select" required>
                @foreach($statuses as $status)
                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control">
        </div>

        <div class="mb-3">
            <label for="transaction_date" class="form-label">Date</label>
            <input type="date" name="transaction_date" id="transaction_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection

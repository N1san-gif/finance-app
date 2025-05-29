<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report</h2>
    </x-slot>

    <div class="p-6 max-w-6xl mx-auto space-y-6">

        <div class="bg-white shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-2">General Information</h3>
            <p>Income: ${{ number_format($totalIncome, 2) }}</p>
            <p>Expenses: ${{ number_format($totalExpenses, 2) }}</p>
            <p>Balance: ${{ number_format($totalIncome - $totalExpenses, 2) }}</p>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-2">By Category</h3>
            <ul>
                @foreach ($byCategory as $category => $sum)
                    <li>{{ $category }} — ${{ number_format($sum, 2) }}</li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h3 class="text-lg font-semibold mb-2">Analysis</h3>
                <p>Number of Transactions: {{ isset($transactions) ? $transactions->count() : 0 }}</p>
                <p>Average Amount: {{ isset($transactions) ? number_format($transactions->avg('amount'), 2) : '0.00' }}</p>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-semibold">Actions</h3>
            <div class="flex space-x-4 mt-2">
                <form action="{{ route('reports.pdf') }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $start_date ?? request('from') }}">
                    <input type="hidden" name="end_date" value="{{ $end_date ?? request('to') }}">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Download PDF
                    </button>
                </form>

                <form action="{{ route('reports.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $start_date ?? request('from') }}">
                    <input type="hidden" name="end_date" value="{{ $end_date ?? request('to') }}">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Send by Email
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

    </div>
</x-app-layout>

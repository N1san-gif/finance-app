<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\Status; 
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->with(['category', 'status']) 
            ->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        $statuses = Status::all(); 
        return view('transactions.create', compact('categories', 'statuses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'status_id' => 'required|exists:statuses,id', 
        ]);
        
        $validated['user_id'] = auth()->id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Транзакция успешно добавлена.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $categories = Category::where('user_id', auth()->id())->get();
        $statuses = Status::all(); 
        return view('transactions.edit', compact('transaction', 'categories', 'statuses'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'transaction_date' => 'required|date',
            'status_id' => 'required|exists:statuses,id', 
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Транзакция успешно обновлена.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Транзакция перемещена в корзину.');
    }

    public function trashed()
    {
        $transactions = Transaction::onlyTrashed()
            ->where('user_id', auth()->id())
            ->paginate(10);

        return view('transactions.trashed', compact('transactions'));
    }

    public function restore($id)
    {
        $transaction = Transaction::withTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $this->authorize('restore', $transaction);

        $transaction->restore();

        return redirect()->route('transactions.trashed')->with('success', 'Транзакция успешно восстановлена.');
    }

    public function forceDelete($id)
    {
        $transaction = Transaction::withTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $this->authorize('forceDelete', $transaction);

        $transaction->forceDelete();

        return redirect()->route('transactions.trashed')->with('success', 'Транзакция окончательно удалена.');
    }
    public function report(Request $request)
{
    $request->validate([
        'from' => 'required|date',
        'to' => 'required|date|after_or_equal:from',
    ]);

    $from = $request->input('from');
    $to = $request->input('to');

    $transactions = Transaction::where('user_id', auth()->id())
        ->whereBetween('transaction_date', [$from, $to])
        ->with('category')
        ->get();

    $totalIncome = $transactions->where('amount', '>', 0)->sum('amount');
    $totalExpenses = $transactions->where('amount', '<', 0)->sum('amount');

    return view('reports.transactions', compact('from', 'to', 'transactions', 'totalIncome', 'totalExpenses'));
}
public function show(Transaction $transaction)
{
    $this->authorize('view', $transaction); // если есть policy

    return view('transactions.show', compact('transaction'));
}

}


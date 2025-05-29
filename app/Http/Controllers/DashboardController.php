<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Все транзакции пользователя
        $transactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->get();

        // Доходы
        $totalIncome = Transaction::whereHas('category', function ($query) {
            $query->where('type', 'income');
        })->where('user_id', $user->id)->sum('amount');

        // Расходы
        $totalExpenses = Transaction::whereHas('category', function ($query) {
            $query->where('type', 'expense');
        })->where('user_id', $user->id)->sum('amount');

        // Баланс
        $balance = $totalIncome - $totalExpenses;

        // 🔥 Последние 5 транзакций
        $latestTransactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->orderByDesc('transaction_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'balance',
            'totalIncome',
            'totalExpenses',
            'transactions',
            'latestTransactions' // 👈 обязательно передаём это!
        ));
    }
}

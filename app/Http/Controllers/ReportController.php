<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Transaction;
use App\Mail\ReportEmail;
use PDF;

class ReportController extends Controller
{
    // Displays the empty report view with default values
    public function index()
    {
        $totalIncome = 0;
        $totalExpenses = 0;
        $byCategory = [];
        $transactions = collect();

        return view('reports.index', compact('totalIncome', 'totalExpenses', 'byCategory', 'transactions'));
    }

    // Generates the report data to show on the page based on date range
    public function generate(Request $request)
    {
        $user = Auth::user();

        // Validate input dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        // Fetch transactions for the authenticated user in the specified date range
        $transactions = Transaction::with('category', 'status')
            ->where('user_id', $user->id)
            ->whereBetween('transaction_date', [$request->from, $request->to])
            ->get();

        // Calculate total income and expenses
        $totalIncome = $transactions->filter(fn($t) => $t->category && $t->category->type === 'income')->sum('amount');
        $totalExpenses = $transactions->filter(fn($t) => $t->category && $t->category->type === 'expense')->sum('amount');

        // Group transactions by category and sum amounts per category
        $byCategory = $transactions->groupBy(fn($t) => $t->category->name ?? 'Без категории')->map->sum('amount');

        // Return the view with calculated data
        return view('reports.index', compact('transactions', 'totalIncome', 'totalExpenses', 'byCategory'));
    }

    // Generates a downloadable PDF report of the transactions
    public function generatePdf(Request $request)
    {
        $user = Auth::user();

        // Validate input dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        // Fetch transactions in the specified date range
        $transactions = Transaction::with('category', 'status')
            ->where('user_id', $user->id)
            ->whereBetween('transaction_date', [$request->from, $request->to])
            ->get();

        // Calculate totals
        $totalIncome = $transactions->filter(fn($t) => $t->category && $t->category->type === 'income')->sum('amount');
        $totalExpenses = $transactions->filter(fn($t) => $t->category && $t->category->type === 'expense')->sum('amount');
        $byCategory = $transactions->groupBy(fn($t) => $t->category->name ?? 'Без категории')->map->sum('amount');

        // Load the data into a Blade view and generate a PDF
        $pdf = \PDF::loadView('reports.transactions', compact('transactions', 'totalIncome', 'totalExpenses', 'byCategory', 'request'));

        // Return the PDF file for download
        return $pdf->download('transactions_report.pdf');
    }

    // Sends a report email with detailed transactions to the user's email address
    public function sendReport(Request $request)
    {
        // Validate input dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $user = Auth::user();

        // Fetch transactions for the date range
        $transactions = Transaction::with('category', 'status')
            ->where('user_id', $user->id)
            ->whereBetween('transaction_date', [$request->from, $request->to])
            ->get();

        // Prepare the report data
        $reportData = [
            'title' => 'Financial Report from ' . $request->from . ' to ' . $request->to,
            'date' => now()->format('Y-m-d'),
            'transactions' => $transactions,
        ];

        // Send the report via email using a mailable class
        Mail::to($user->email)->send(new ReportEmail($user, $reportData));

        // Redirect back with a success message
        return back()->with('success', 'The report has been successfully sent to your email!');
    }

    // Another version of sending report email with additional income/expense calculations
    public function sendEmail(Request $request)
    {
        // Validate input dates
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        $user = auth()->user();

        // Get transactions for the user in the specified range
        $transactions = Transaction::whereBetween('transaction_date', [$request->from, $request->to])
                                   ->where('user_id', $user->id)
                                   ->get();

        // Calculate income and expenses
        $totalIncome = $transactions->filter(fn($t) => $t->category && $t->category->type === 'income')->sum('amount');
        $totalExpenses = $transactions->filter(fn($t) => $t->category && $t->category->type === 'expense')->sum('amount');

        // Prepare the report data
        $reportData = [
            'from' => $request->from,
            'to' => $request->to,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'transactions' => $transactions,
        ];

        // Send the report email
        Mail::to($user->email)->send(new ReportEmail($user, $reportData));

        // Redirect back with a success message
        return back()->with('success', 'The report has been successfully sent to your email!');
    }
}

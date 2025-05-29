<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class ReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $reportData;

    public function __construct(User $user, array $reportData)
    {
        $this->user = $user;
        $this->reportData = $reportData;
    }

    public function build()
    {
        $pdf = Pdf::loadView('reports.transactions', [
            'from' => $this->reportData['from'],
            'to' => $this->reportData['to'],
            'totalIncome' => $this->reportData['totalIncome'],
            'totalExpenses' => $this->reportData['totalExpenses'],
            'transactions' => $this->reportData['transactions'],
        ]);

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Ваш фінансовий звіт')
                    ->view('emails.report_template')  // email-шаблон для тексту листа
                    ->with('name', $this->user->name)
                    ->attachData($pdf->output(), "financial-report.pdf", [
                        'mime' => 'application/pdf',
                    ]);
    }
}

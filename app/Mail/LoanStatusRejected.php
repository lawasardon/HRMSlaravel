<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatusRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $reason_of_rejection;

    public function __construct(Loan $loan, $reason_of_rejection)
    {
        $this->loan = $loan;
        $this->reason_of_rejection = $reason_of_rejection;
    }

    public function build()
    {
        return $this->view('emails.loan_rejected')
            ->subject('Your Loan Has Been Rejected')
            ->with([
                'name' => $this->loan->employee->user->name,
                'reasonOfRejection' => $this->reason_of_rejection,
            ]);
    }
}

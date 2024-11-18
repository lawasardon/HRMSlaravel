<?php

namespace App\Mail;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatusApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;
    public $reason_of_rejection;

    public function __construct(Loan $loan, $reason_of_rejection = null)
    {
        $this->loan = $loan;
        $this->reason_of_rejection = $reason_of_rejection;
    }

    public function build()
    {
        return $this->view('emails.loan_approved')
            ->subject('Your Loan Has Been Approved')
            ->with([
                'name' => $this->loan->employee->user->name,
                'loanAmount' => $this->loan->amount,
                'reasonOfRejection' => $this->reason_of_rejection,
            ]);
    }
}

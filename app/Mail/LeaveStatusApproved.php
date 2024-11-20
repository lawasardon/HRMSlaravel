<?php

namespace App\Mail;

use App\Models\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveStatusApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $leave;
    public $reason_of_rejection;

    public function __construct(Leave $leave, $reason_of_rejection = null)
    {
        $this->leave = $leave;
        $this->reason_of_rejection = $reason_of_rejection ?? '';
    }

    public function build()
    {
        // Get user name safely
        $name = optional($this->leave->user)->name ?? 'Employee';

        return $this->view('emails.leave_approved')
            ->subject('Your Leave Has Been Approved')
            ->with([
                'name' => $name,
                'leaveAmount' => $this->leave->total_days_leave ?? 0,
                'reasonOfRejection' => $this->reason_of_rejection,
            ]);
    }
}

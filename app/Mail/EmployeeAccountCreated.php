<?php

namespace App\Mail;

use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    public $password;
    public $idNumber;

    public function __construct(Employee $employee, $password, $idNumber)
    {
        $this->employee = $employee;
        $this->password = $password;
        $this->idNumber = $idNumber;
    }

    public function build()
    {
        return $this->view('emails.employee_account_created')
            ->subject('Your Account Has Been Created')
            ->with([
                'name' => $this->employee->user->name,
                'position' => $this->employee->position,
                'email' => $this->employee->email,
                'password' => $this->password,
                'idNumber' => $this->idNumber,
            ]);
    }
}

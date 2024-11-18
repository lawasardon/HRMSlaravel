<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $table = 'loan_payments';

    protected $fillable = [
        'employee_id',
        'id_number',
        'loan_id',
        'amount',
        'payment_date',
        'payment_type',
        'remarks',
        'status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}

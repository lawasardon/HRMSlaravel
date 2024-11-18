<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'loan';
    protected $fillable =
        [
            'employee_id',
            'id_number',
            'date_filed',
            'amount',
            'interest',
            'total',
            'status',
            'remarks',
            'terms_of_loan',
            'deduction_per_salary',
            'reason_of_loan',
            'reason_of_rejection',
        ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class, 'id_number');
    }
}

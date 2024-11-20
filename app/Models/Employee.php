<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'user_id',
        'department_id',
        'id_number',
        'name',
        'email',
        'address',
        'phone',
        'gender',
        'birthday',
        'religion',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function payroll()
    {
        return $this->hasOne(Payroll::class);
    }

    public function deduction()
    {
        return $this->hasOne(Deduction::class, 'id_number', 'id_number');
    }

    public function employeeRate()
    {
        return $this->hasOne(EmployeeRates::class, 'id_number', 'id_number');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'id_number', 'id_number');
    }

    public function loan()
    {
        return $this->hasOne(Loan::class, 'id_number', 'id_number');
    }

}

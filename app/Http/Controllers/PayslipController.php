<?php

namespace App\Http\Controllers;

use App\Models\Deduction;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{

    public function viewPayslip()
    {
        return view('payslip.index');
    }
    public function payslipData()
    {
        $userId = auth()->user()->employee->id_number;
        $payslipData = Payroll::where('id_number', $userId)->get();
        return response()->json($payslipData);
    }

    public function downloadPayslip(Request $request)
    {
        $userId = auth()->user()->employee->id_number;
        $duration = $request->input('duration');

        $payslip = Payroll::where('id_number', $userId)
            ->where('duration', $duration)
            ->firstOrFail();

        $deductions = Deduction::where('id_number', $userId)
            ->firstOrFail();


        $employee = auth()->user()->employee;

        $pdf = PDF::loadView('payslip.pdf', [
            'payslip' => $payslip,
            'deductions' => $deductions,
            'employee' => $employee
        ]);

        return $pdf->download("payslip_{$duration}.pdf");
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Mail\LoanStatusApproved;
use App\Mail\LoanStatusRejected;
use Illuminate\Support\Facades\Auth; // Ensure you have this import
use Illuminate\Support\Facades\Mail;

class LoanController extends Controller
{

    public function myLoans()
    {
        return view('loan.employee.index');
    }

    public function myLoansData()
    {
        $user = Auth::user();


        $loans = Loan::with('employee')
            ->where('id_number', $user->employee->id_number)
            ->get();

        return response()->json($loans);
    }

    public function createLoan()
    {

        return view('loan.employee.create', [
            'user' => auth()->user()
        ]);
    }

    public function storeLoan(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return response()->json(['message' => 'No employee linked to this user'], 400);
        }

        $validatedData = $request->validate([
            'date_filed' => 'required|string',
            'amount' => 'required|numeric',
            'interest' => 'required|numeric',
            'total' => 'required|numeric',
            'terms_of_loan' => 'required|integer',
            'deduction_per_salary' => 'required|numeric',
            'reason_of_loan' => 'required|string',
        ]);

        $loan = Loan::create([
            'employee_id' => $employee->id,
            'id_number' => $employee->id_number,
            'date_filed' => $request->date_filed,
            'amount' => $request->amount,
            'interest' => $request->interest,
            'total' => $request->total,
            'status' => 'pending',
            'remarks' => 'pending',
            'terms_of_loan' => $request->terms_of_loan,
            'deduction_per_salary' => $request->deduction_per_salary,
            'reason_of_loan' => $request->reason_of_loan,

        ]);

        return response()->json(['message' => 'Loan added successfully!'], 200);
    }


    //ADMIN
    public function showAquaLoans()
    {
        return view('loan.aqua.index');
    }

    public function showAquaLoansData()
    {
        // Fetch loans where the related employee has department_id 1
        $aquaLoans = Loan::with('employee')
            ->whereHas('employee', function ($query) {
                $query->where('department_id', 1);  // Filter employees with department_id 1
            })
            ->get();

        return response()->json($aquaLoans);
    }

    public function showLamininLoans()
    {
        return view('loan.laminin.index');
    }

    public function showLamininLoansData()
    {
        // Fetch loans where the related employee has department_id 1
        $aquaLoans = Loan::with('employee')
            ->whereHas('employee', function ($query) {
                $query->where('department_id', 2);  // Filter employees with department_id 1
            })
            ->get();

        return response()->json($aquaLoans);
    }

    public function updateAuaLoan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
            'reason_of_rejection' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->update($validatedData);

        $status = $validatedData['status'];
        $reason_of_rejection = $validatedData['reason_of_rejection'] ?? null;

        if ($status == 'approved') {
            Mail::to($loan->employee)->send(new LoanStatusApproved($loan, $reason_of_rejection));

        }

        if ($status == 'rejected') {
            Mail::to($loan->employee)->send(new LoanStatusRejected($loan, $reason_of_rejection));

        }

        return response()->json(['message' => 'Loan updated successfully', 'employee' => $loan], 200);
    }

    public function updateLamininLoan(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
            'reason_of_rejection' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($id);
        $loan->update($validatedData);

        $status = $validatedData['status'];
        $reason_of_rejection = $validatedData['reason_of_rejection'] ?? null;

        if ($status == 'approved') {
            Mail::to($loan->employee)->send(new LoanStatusApproved($loan, $reason_of_rejection));

        }

        if ($status == 'rejected') {
            Mail::to($loan->employee)->send(new LoanStatusRejected($loan, $reason_of_rejection));

        }

        return response()->json(['message' => 'Loan updated successfully', 'employee' => $loan], 200);
    }


}

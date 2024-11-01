<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Deduction;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\EmployeeRates;

class PayrollController extends Controller
{

    public function showAllEmployeeRates()
    {
        return view('payroll.rates');
    }

    public function showAllEmployeeRatesData()
    {
        $employeeRates = Employee::with('employeeRate', 'deduction')->get()
            ->map(function ($payroll) {
                return [
                    'id' => $payroll->id,
                    'id_number' => $payroll->id_number,
                    'department_id' => $payroll->department_id,
                    'name' => $payroll->name,
                    'monthly_rate' => $payroll->employeeRate->monthly_rate ?? null,
                    'rate_per_day' => $payroll->employeeRate->rate_perday ?? null,
                    'sss' => $payroll->deduction->sss ?? null,
                    'pag_ibig' => $payroll->deduction->pag_ibig ?? null,
                    'phil_health' => $payroll->deduction->phil_health ?? null,
                ];
            });
        return response()->json($employeeRates);
    }

    public function storeRateAndDeduction(Request $request)
    {
        $validatedData = $request->validate([
            'monthly_rate' => 'nullable|numeric',
            'sss' => 'nullable|numeric',
            'pag_ibig' => 'nullable|numeric',
            'phil_health' => 'nullable|numeric',
            'id_number' => 'required|integer',
            'employee_id' => 'required|integer',
        ]);

        $monthlyRate = EmployeeRates::updateOrCreate(
            [
                'id_number' => $validatedData['id_number'],
                'employee_id' => $validatedData['employee_id']
            ],
            [
                'monthly_rate' => $validatedData['monthly_rate'] ?? 0,
                'sss' => $validatedData['sss'] ?? 0,
                'pag_ibig' => $validatedData['pag_ibig'] ?? 0,
                'phil_health' => $validatedData['phil_health'] ?? 0,
            ]
        );

        if (isset($validatedData['monthly_rate'])) {
            $monthlyRate->monthly_rate = $validatedData['monthly_rate'];

            $daysInMonth = 30;
            $weekdays = $daysInMonth - (4 * 2);

            $dailyRate = $validatedData['monthly_rate'] / $weekdays;

            $monthlyRate->rate_perday = round($dailyRate, 2);
        }

        $monthlyRate->save();

        $deductionData = [
            'sss' => $validatedData['sss'] ?? 0,
            'pag_ibig' => $validatedData['pag_ibig'] ?? 0,
            'phil_health' => $validatedData['phil_health'] ?? 0,
            'employee_id' => $monthlyRate->employee->id,
        ];

        Deduction::updateOrCreate(
            ['id_number' => $monthlyRate->employee->id_number],
            $deductionData
        );

        return response()->json(['message' => 'Updated successfully', 'rate' => $monthlyRate], 200);
    }

    public function showAquaPayroll()
    {
        return view('payroll.aqua.index');
    }

    public function aquaShowEditModal($id)
    {
        $payroll = Payroll::with('employee')->findOrFail($id);
        return response()->json($payroll);
    }

    public function showAquaPayrollData()
    {
        $aquaPayroll = Payroll::where('department_id', 1)->with('employee')->get();

        return response()->json($aquaPayroll);
    }

    public function aquaPayrollCalculation()
    {
        $aquaAttendance = Attendance::with([
            'payroll' => function ($query) {
                $query->with(['deduction']);
            },
            'employee' => function ($query) {
                $query->with(['employeeRate', 'deduction']);
            }
        ])
            ->where('department', 'aqua')
            ->get();

        $summarizedData = [];

        foreach ($aquaAttendance as $attendance) {
            $idNumber = $attendance->id_number;
            $date = Carbon::parse($attendance->date);

            $firstDayOfMonth = $date->copy()->startOfMonth();
            $lastDayOfMonth = $date->copy()->endOfMonth();
            $midMonth = $date->copy()->startOfMonth()->addDays(14);

            if ($date->between($firstDayOfMonth, $midMonth)) {
                $period = 'first_half';
                $periodStart = $firstDayOfMonth;
                $periodEnd = $midMonth;
            } elseif ($date->between($midMonth->addDay(), $lastDayOfMonth)) {
                $period = 'second_half';
                $periodStart = Carbon::parse($date)->startOfMonth()->addDays(15);
                $periodEnd = $lastDayOfMonth;
            } else {
                continue;
            }

            $key = $idNumber . '_' . $period . '_' . $date->format('Y_m');

            if (!isset($summarizedData[$key])) {
                $employeeRate = EmployeeRates::where('id_number', $attendance->id_number)->first();
                $govDeduction = Deduction::where('id_number', $attendance->id_number)->first();

                $totalGovDeduction = 0;
                if ($govDeduction) {
                    $totalGovDeduction = ($govDeduction->sss + $govDeduction->pag_ibig + $govDeduction->phil_health) / 2;
                }

                $durationString = sprintf(
                    "%s %d - %s %d, %d",
                    $periodStart->format('F'),
                    $periodStart->day,
                    $periodEnd->format('F'),
                    $periodEnd->day,
                    $periodEnd->year
                );

                $storedPayroll = $attendance->payroll;

                $workingDays = 0;
                $currentDate = $periodStart->copy();

                while ($currentDate <= $periodEnd) {
                    if ($currentDate->isWeekday()) {
                        $workingDays++;
                    }
                    $currentDate->addDay();
                }

                $summarizedData[$key] = [
                    'id' => $storedPayroll ? $storedPayroll->id : null,
                    'id_number' => $idNumber,
                    'department_id' => $attendance->department === 'aqua' ? 1 : 2,
                    'name' => $attendance->name,
                    'monthly_rate' => $employeeRate ? $employeeRate->monthly_rate : 0,
                    'rate_perday' => $employeeRate ? $employeeRate->rate_perday : 0,
                    'total_working_days' => $workingDays,
                    'over_time' => $storedPayroll && $storedPayroll->over_time ? $storedPayroll->over_time : 0,
                    'total_gov_deduction' => $totalGovDeduction,
                    'late' => 0,
                    'loan' => 0,
                    'salary' => $storedPayroll && $storedPayroll->salary ? $storedPayroll->salary : 0,
                    'duration' => $durationString,
                    'status' => $storedPayroll ? $storedPayroll->status : 'pending'
                ];
            }

            if ($date->isWeekday()) {
                if ($attendance->time_in > '08:10:00') {
                    $lateMinutes = Carbon::parse($attendance->time_in)
                        ->diffInMinutes(Carbon::createFromTimeString('08:10:00'));
                    $summarizedData[$key]['late'] += $lateMinutes;
                }
            }
        }

        foreach ($summarizedData as &$data) {
            if (!$data['salary']) {
                $baseSalary = $data['rate_perday'] * $data['total_working_days'];
                $lateDeduction = $data['late'] * 10;
                $govDeduction = $data['total_gov_deduction'];

                $overtimePay = 0;
                if ($data['over_time'] > 0) {
                    $hourlyRate = $data['rate_perday'] / 8;
                    $overtimeRate = $hourlyRate * 1.25;
                    $overtimePay = $overtimeRate * $data['over_time'];
                }

                $data['salary'] = round($baseSalary + $overtimePay - $lateDeduction - $govDeduction, 2);
            }
        }

        return response()->json(array_values($summarizedData));
    }


    public function updateAquaPayroll(Request $request, $id)
    {
        $validatedData = $request->validate([
            'duration' => 'nullable|string',
            'total_working_days' => 'nullable|numeric',
            'over_time' => 'nullable|numeric',
            'salary' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $payroll = Payroll::findOrFail($id);

        if ($request->has('duration')) {
            $payroll->duration = $validatedData['duration'];
        }

        if ($request->has('total_working_days')) {
            $payroll->total_working_days = $validatedData['total_working_days'];
        }

        if ($request->has('over_time')) {
            $payroll->over_time = $validatedData['over_time'];
        }

        if ($request->has('salary')) {
            $payroll->salary = $validatedData['salary'];
        }

        if ($request->has('status')) {
            $payroll->status = $validatedData['status'];
        }

        $payroll->save();

        return response()->json([
            'message' => 'Updated successfully',
            'payroll' => $payroll
        ], 200);
    }
}

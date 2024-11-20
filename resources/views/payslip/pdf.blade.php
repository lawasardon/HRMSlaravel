<!DOCTYPE html>
<html>

<head>
    <title>Payslip - {{ $payslip->duration }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Employee Payslip</h2>
        <p>{{ $employee->name }} </p>
    </div>

    <table>
        <tr>
            <th>Period</th>
            <td>{{ $payslip->duration }}</td>
        </tr>
        <tr>
            <th>Basic Salary</th>
            <td>{{ $payslip->salary }}</td>
        </tr>
        <tr>
            <th>Overtime</th>
            <td>{{ $payslip->over_time }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Deductions</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Loan</td>
                <td>{{ $loan->deduction_per_salary }}</td>
            </tr>
            <tr>
                <td>SSS</td>
                <td>{{ $deductions->sss / 2 }}</td>
            </tr>
            <tr>
                <td>Pag-IBIG</td>
                <td>{{ $deductions->pag_ibig / 2 }}</td>

            </tr>
            <tr>
                <td>PhilHealth</td>
                <td>{{ $deductions->phil_health / 2 }}</td>
            </tr>
            <tr class="total">
                <td>Total Deductions</td>
                <td>{{ $payslip->total_deduction + $loan->deduction_per_salary }}</td>

            </tr>
        </tbody>
    </table>

    <table>
        <tr class="total">
            <th>Net Pay</th>
            {{-- <td>{{ $payslip->salary - $payslip->total_deduction }}</td> --}}
            <td>{{ $payslip->salary }}</td>
        </tr>
    </table>
</body>

</html>

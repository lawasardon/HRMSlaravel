<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class CountController extends Controller
{
    public function countData()
    {
        $countAqua = Employee::where('department_id', 1)->count();
        $laminin = Employee::where('department_id', 2)->count();
        $totalEmployee = $countAqua + $laminin;
        $totalDepartment = Department::count();

        return response()->json([
            'countAqua' => $countAqua,
            'laminin' => $laminin,
            'totalEmployee' => $totalEmployee,
            'totalDepartment' => $totalDepartment
        ]);
    }

}

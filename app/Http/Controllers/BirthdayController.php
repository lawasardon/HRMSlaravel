<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class BirthdayController extends Controller
{
    public function birthdayData()
    {
        $currentMonth = now()->month;
        $birthdays = Employee::with('user')->whereMonth('birthday', $currentMonth)->get();
        return response()->json($birthdays);
    }

}

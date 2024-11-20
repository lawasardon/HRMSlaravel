<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceTemplateExport;

class AttendanceController extends Controller
{
    public function attendanceList()
    {
        return view('attendance.index');
    }

    public function attendanceDownloadableTemplate()
    {
        return Excel::download(new AttendanceTemplateExport, 'attendance_template.xlsx');
    }

    public function attendanceShowUploadPage()
    {
        return view('attendance.upload');
    }

    public function attendanceUpload(Request $request)
    {
        $request->validate([
            'attendance_file' => 'required|mimes:xlsx,csv',
        ]);

        \DB::transaction(function () use ($request) {
            Excel::import(new AttendanceImport, $request->file('attendance_file'));
        });

        return response()->json(['message' => 'Attendance uploaded successfully']);
    }

    public function attendanceListAllEmployee()
    {
        return view('attendance.index');
    }

    public function attendanceListAllEmployeeData()
    {
        $allEmployee = Attendance::all();
        return response()->json($allEmployee);
    }

    public function attendanceListAqua()
    {
        return view('attendance.aqua.index');
    }

    public function attendanceListAquaData()
    {
        $aquaAttendance = Attendance::where('department', 'aqua')->get();
        return response()->json($aquaAttendance);
    }

    public function attendanceListLaminin()
    {
        return view('attendance.laminin.index');
    }

    public function attendanceListLamininData()
    {
        $lamininAttendance = Attendance::where('department', 'laminin')->get();
        return response()->json($lamininAttendance);
    }







    //EMPLOYEE
    public function myAttendance()
    {
        $userId = auth()->user()->employee->id_number;
        $month = now()->month;
        $year = now()->year;

        $attendance = Attendance::where('id_number', $userId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->keyBy('date');

        return view('attendance.employee.index', compact('attendance', 'month', 'year'));
    }

    public function attendanceAquaSearch(Request $request)
    {
        $request->validate([
            'searchQuery' => 'nullable|string|max:255',
        ]);

        $searchQuery = $request->input('searchQuery');

        $employees = \App\Models\Attendance::query()
            ->where('department', 'aqua')
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json($employees);
    }

    public function attendanceLamininSearch(Request $request)
    {
        $request->validate([
            'searchQuery' => 'nullable|string|max:255',
        ]);

        $searchQuery = $request->input('searchQuery');

        $employees = \App\Models\Attendance::query()
            ->where('department', 'laminin')
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->get();

        return response()->json($employees);
    }

    public function attendanceSearchAllEmployee(Request $request)
    {
        $request->validate([
            'searchQuery' => 'nullable|string|max:255',
        ]);

        $searchQuery = $request->input('searchQuery');

        $employees = \App\Models\Attendance::query()
            ->where(function ($query) use ($searchQuery) {
                $query->where('name', 'LIKE', '%' . $searchQuery . '%');
            })
            ->where(function ($query) {
                $query->where('department', 'laminin')
                    ->orWhere('department', 'aqua');
            })
            ->get();

        return response()->json($employees);
    }





}

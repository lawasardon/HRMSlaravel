<?php

namespace App\Http\Controllers;

use App\Mail\LeaveStatusApproved;
use App\Mail\LeaveStatusRejected;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function aquaLeaveList()
    {
        return view('leave.aqua.index');
    }

    public function lamininLeaveList()
    {
        return view('leave.laminin.index');
    }

    public function aquaLeaveListData()
    {
        $aquaLeaveList = Leave::with('department')
            ->where('department_id', 1)
            ->get();

        return response()->json($aquaLeaveList);
    }

    public function lamininLeaveListData()
    {
        $lamininLeaveList = Leave::with('department')
            ->where('department_id', 2)
            ->get();

        return response()->json($lamininLeaveList);
    }

    public function aquaLeaveListUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
            'reason_of_rejection' => 'nullable|string',
        ]);

        // Fetch the leave record
        $leave = Leave::findOrFail($id);

        // Get user directly from leave
        $user = $leave->user;

        if ($user) {
            $email = $user->email;

            // Use null coalescing operator to provide a default empty string
            $reasonOfRejection = $request->input('reason_of_rejection', '');

            // Send mail based on leave status
            if ($request->status === 'approved') {
                Mail::to($email)->send(new LeaveStatusApproved($leave, $reasonOfRejection));
            }

            if ($request->status === 'rejected') {
                Mail::to($email)->send(new LeaveStatusRejected($leave, $reasonOfRejection));
            }
        } else {
            return response()->json(['message' => 'No associated user found for this leave'], 400);
        }

        // Update the leave status and reason of rejection
        $leave->update($validatedData);

        return response()->json(['message' => 'Leave updated successfully', 'leave' => $leave], 200);
    }

    public function lamininLeaveListUpdate(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|string',
            'reason_of_rejection' => 'nullable|string',
        ]);

        // Fetch the leave record
        $leave = Leave::findOrFail($id);

        // Get user directly from leave
        $user = $leave->user;

        if ($user) {
            $email = $user->email;

            // Send mail based on leave status
            if ($request->status === 'approved') {
                Mail::to($email)->send(new LeaveStatusApproved($leave, $validatedData['reason_of_rejection']));
            }

            if ($request->status === 'rejected') {
                Mail::to($email)->send(new LeaveStatusRejected($leave, $validatedData['reason_of_rejection']));
            }
        } else {
            return response()->json(['message' => 'No associated user found for this leave'], 400);
        }

        // Update the leave status and reason of rejection
        $leave->update($validatedData);

        return response()->json(['message' => 'Leave updated successfully', 'leave' => $leave], 200);

    }

    public function leaveList()
    {
        return view('leave.employee.index');
    }

    //EMPLOYEE
    public function leaveListData()
    {
        $leave = Leave::with('department')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json($leave);
    }

    public function getDepartmentIdData()
    {
        $user = Auth::user();
        $departmentId = $user->employee->department_id;
        $userName = $user->name;

        return response()->json([
            'department_id' => $departmentId,
            'name' => $userName,
        ]);
    }

    public function createLeave()
    {
        return view('leave.employee.create');
    }

    public function storeLeave(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'date_filed' => 'required|string',
            'name' => 'required|string',
            'date_start' => 'required|string',
            'date_end' => 'required|string',
            'total_days_leave' => 'required|integer',
            'type_of_day' => 'required|string',
            'type_of_leave' => 'required|string',
            'reason_to_leave' => 'required|string',
            'status' => 'required|string',
        ]);

        $validatedData['user_id'] = auth()->id();

        $leave = Leave::create($validatedData);

        return response()->json(['message' => 'Leave submitted successfully', 'employee' => $leave], 201);
    }
}

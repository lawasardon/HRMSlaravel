<?php

use App\Http\Controllers\CountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\StaticPagesController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/user/login', [AuthController::class, 'userLoggedIn'])->name('user.login');

Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [StaticPagesController::class, 'showIndex'])->name('home');
    Route::get('/get/news', [NewsController::class, 'getNewsData'])->name('get.news.data');
    Route::get('/profile/settings', [AuthController::class, 'profileSettings'])->name('profile.settings');
    Route::get('/profile/settings/data', [AuthController::class, 'profileSettingsData'])->name('profile.settings.data');
    Route::post('/update/password', [AuthController::class, 'updatePassword'])->name('update.password');
    Route::post('/update/profile/picture', [AuthController::class, 'updateProfilePicture'])->name('update.profile.picture');
    Route::get('/birthday/data', [BirthdayController::class, 'birthdayData'])->name('birthday.data');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('/aqua/department', [DepartmentController::class, 'showAquaDepartment'])->name('show.aqua.department');
    });

    Route::group(['middleware' => ['role:admin|hr']], function () {
        Route::get('/aqua/employee/list', [DepartmentController::class, 'showAquaEmployeeList'])->name('show.aqua.employee.list');
        Route::get('/aqua/employee/list/data', [DepartmentController::class, 'aquaEmployeeListData'])->name('aqua.employee.list.data');
        Route::get('/aqua/add/employee', [DepartmentController::class, 'aquaAddEmployee'])->name('aqua.add.employee');
        Route::post('/aqua/store/employee', [DepartmentController::class, 'aquaStoreEmployee'])->name('aqua.store.employee');

        Route::get('/laminin/employee/list', [DepartmentController::class, 'showLamininEmployeeList'])->name('show.laminin.employee.list');
        Route::get('/laminin/employee/list/data', [DepartmentController::class, 'lamininEmployeeListData'])->name('laminin.employee.list.data');
        Route::get('/laminin/add/employee', [DepartmentController::class, 'lamininAddEmployee'])->name('laminin.add.employee');
        Route::post('/laminin/store/employee', [DepartmentController::class, 'lamininStoreEmployee'])->name('laminin.store.employee');

        Route::get('/aqua/leave/list', [LeaveController::class, 'aquaLeaveList'])->name('aqua.leave.list');
        Route::get('/aqua/leave/list/data', [LeaveController::class, 'aquaLeaveListData'])->name('aqua.leave.list.data');
        Route::post('/aqua/leave/list/update/{id}', [LeaveController::class, 'aquaLeaveListUpdate'])->name('aqua.leave.list.update');

        Route::get('/laminin/leave/list', [LeaveController::class, 'lamininLeaveList'])->name('laminin.leave.list');
        Route::get('/laminin/leave/list/data', [LeaveController::class, 'lamininLeaveListData'])->name('laminin.leave.list.data');
        Route::post('/laminin/leave/list/update/{id}', [LeaveController::class, 'lamininLeaveListUpdate'])->name('laminin.leave.list.update');

        // Route::get('/attendance/list', [AttendanceController::class, 'attendanceList'])->name('attendance.list');
        Route::get('/attendance/download/template', [AttendanceController::class, 'attendanceDownloadableTemplate'])->name('attendance.downloadable.template');
        Route::get('/attendance/show/upload/page', [AttendanceController::class, 'attendanceShowUploadPage'])->name('attendance.show.upload.page');
        Route::post('/attendance/upload', [AttendanceController::class, 'attendanceUpload'])->name('attendance.upload');

        Route::get('/attendance/list/all/employee', [AttendanceController::class, 'attendanceListAllEmployee'])->name('attendance.list.all.employee');
        Route::get('/attendance/list/all/employee/data', [AttendanceController::class, 'attendanceListAllEmployeeData'])->name('attendance.list.all.employee.data');
        Route::get('/attendance/list/aqua', [AttendanceController::class, 'attendanceListAqua'])->name('attendance.list.aqua');
        Route::get('/attendance/list/aqua/data', [AttendanceController::class, 'attendanceListAquaData'])->name('attendance.list.aqua.data');
        Route::get('/attendance/list/laminin', [AttendanceController::class, 'attendanceListLaminin'])->name('attendance.list.laminin');
        Route::get('/attendance/list/aqua/laminin', [AttendanceController::class, 'attendanceListLamininData'])->name('attendance.list.laminin.data');

        Route::get('/all/employee/rates', [PayrollController::class, 'showAllEmployeeRates'])->name('show.all.employee.rates');
        Route::get('/all/employee/rates/data', [PayrollController::class, 'showAllEmployeeRatesData'])->name('show.all.employee.rates.data');
        Route::post('/store/employee/rate/{id}', [PayrollController::class, 'storeRateAndDeduction'])->name('store.rate.and.deduction');

        Route::get('/aqua/payroll', [PayrollController::class, 'showAquaPayroll'])->name('show.aqua.payroll');
        Route::get('/aqua/payroll/data', [PayrollController::class, 'showAquaPayrollData'])->name('show.aqua.payroll.data');
        Route::get('/aqua/show/payroll/{id}', [PayrollController::class, 'aquaShowEditModal'])->name('aqua.payroll.show');
        Route::get('/aqua/payroll/calculation', [PayrollController::class, 'aquaPayrollCalculation'])->name('aqua.payroll.calculation');
        Route::post('/aqua/store/Payroll', [PayrollController::class, 'aquaStorePayroll'])->name('aqua.store.payroll');

        Route::get('/laminin/payroll', [PayrollController::class, 'showLamininPayroll'])->name('show.laminin.payroll');
        Route::get('/laminin/payroll/data', [PayrollController::class, 'showLamininPayrollData'])->name('show.laminin.payroll.data');
        Route::get('/laminin/show/payroll/{id}', [PayrollController::class, 'lamininShowEditModal'])->name('laminin.payroll.show');
        Route::get('/laminin/payroll/calculation', [PayrollController::class, 'lamininPayrollCalculation'])->name('laminin.payroll.calculation');
        Route::post('/laminin/store/Payroll', [PayrollController::class, 'lamininStorePayroll'])->name('laminin.store.payroll');

        Route::get('/news/show/create', [NewsController::class, 'showCreate'])->name('show.create.news');
        Route::post('/news/store', [NewsController::class, 'storeNews'])->name('store.news');
        Route::get('/all/news', [NewsController::class, 'allNews'])->name('all.news');
        Route::get('/all/news/data', [NewsController::class, 'allNewsData'])->name('all.news.data');
        Route::delete('/delete/news/{id}', [NewsController::class, 'deleteNews'])->name('delete.news');

        // Route::get('/get/news', [NewsController::class, 'getNewsData'])->name('get.news.data');


        Route::get('/aqua/loans', [LoanController::class, 'showAquaLoans'])->name('show.aqua.loans');
        Route::get('/aqua/loans/data', [LoanController::class, 'showAquaLoansData'])->name('show.aqua.loans.data');
        Route::post('/aqua/loans/update/{id}', [LoanController::class, 'updateAuaLoan'])->name('aqua.loan.list.update');

        Route::get('/laminin/loans', [LoanController::class, 'showLamininLoans'])->name('show.laminin.loans');
        Route::get('/laminin/loans/data', [LoanController::class, 'showLamininLoansData'])->name('show.laminin.loans.data');
        Route::post('/laminin/loans/update/{id}', [LoanController::class, 'updateLamininLoan'])->name('laminin.loan.list.update');

        Route::get('/count/data', [CountController::class, 'CountData'])->name('count.data');


    });

    Route::group(['middleware' => ['role:admin|employee']], function () {
        Route::get('/leave/list', [LeaveController::class, 'leaveList'])->name('employee.leave.list');
        Route::get('/leave/list/data', [LeaveController::class, 'leaveListData'])->name('employee.leave.list.data');
        Route::get('/get/department/id/data', [LeaveController::class, 'getDepartmentIdData'])->name('employee.get.department.id.data');
        Route::get('/leave/create', [LeaveController::class, 'createLeave'])->name('employee.leave.create');
        Route::post('/leave/store', [LeaveController::class, 'storeLeave'])->name('employee.leave.store');

        Route::get('/my/loans', [LoanController::class, 'myLoans'])->name('my.loans');
        Route::get('/my/loans/data', [LoanController::class, 'myLoansData'])->name('my.loans.data');
        Route::get('/create/loan', [LoanController::class, 'createLoan'])->name('create.loan');
        Route::post('/store/loan', [LoanController::class, 'storeLoan'])->name('store.loan');

        Route::get('/my/attendance', [AttendanceController::class, 'myAttendance'])->name('my.attendance');

        Route::get('/view/payslip', [PayslipController::class, 'viewPayslip'])->name('view.payslip');
        Route::get('/payslip/data', [PayslipController::class, 'payslipData'])->name('payslip.data');
        Route::get('/download/payslips', [PayslipController::class, 'downloadPayslip'])->name('download.payslip');

    });
});

<?php

namespace Modules\Core\Http\Controllers;

use Modules\Core\Models;
use Modules\Account\Models\User;
use Modules\Account\Models\UserLog;
use Modules\HRMS\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index(Request $request)
    {
        $statistics = [
            'departments_count' => Models\CompanyDepartment::where('grade_id', userGrades())->count(),
            'positions_count' => Models\CompanyPosition::count(),
            'employees_count' => Employee::where('grade_id', userGrades())->count(),
            'users_count' => User::whereHas('teacher', function($query){
                $query->where('grade_id', userGrades());
            })->orWhereHas('student', function($query){
                $query->where('grade_id', userGrades());
            })->count(),
        ];

        $recent_activities = UserLog::with('user.meta')->whereHas('user.teacher', function($query){
            $query->where('grade_id', userGrades());
        })->latest()->limit(5)->get();

        return view('core::dashboard', compact(
            'statistics',
            'recent_activities'
        ));
    }
}

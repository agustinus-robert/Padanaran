<?php

namespace Modules\Portal\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Models\User;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\HRMS\Models\EmployeeVacation;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\ProductStock;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\Adjustment;
use Modules\Portal\Repositories\ScheduleRepository;
use Modules\Portal\Repositories\ServiceRepository;

class HomeMsdmController extends Controller
{
    use ScheduleRepository, ServiceRepository;
    /**
     * Display home view
     */
    public function index(Request $request)
    {
        $user = $request->user();
        //->filter(fn($leave) => $leave->hasAllApprovableResultIn('APPROVE'))
        $today = date('Y-m-d');
        $leaves_today = EmployeeLeave::with('employee.user.meta')
            ->whereHas('employee', function($query){
                $query->where('grade_id', userGrades());
            })
            ->whereRaw("jsonb_path_exists(dates, '$[*] ? (@.d == \"$today\")')")
            ->get()
            ->filter(fn($v) => $v->hasAllApprovableResultIn('APPROVE'));

        $vacations_today = EmployeeVacation::with('quota.employee.user.meta')
            ->where('grade_id', userGrades())
            ->whereRaw("jsonb_path_exists(dates, '$[*] ? (@.d == \"$today\")')")
            ->get()
            ->filter(fn($v) => $v->hasAllApprovableResultIn('APPROVE'));
            
        return view('portal::homeMsdm', compact('user', 'leaves_today', 'vacations_today'));
    }
}

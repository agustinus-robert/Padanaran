<?php

namespace Modules\HRMS\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Search empls.
     */
    public function search(Request $request)
    {
        return response()->success([
            'message' => 'Berikut adalah daftar karyawan berdasarkan kueri Anda.',
            'employees' => Employee::whereHas('user', fn ($user) => $user->search($request->get('q')))->limit(8)->get()->map(fn ($v) => [
                'id' => $v->id,
                'text' => $v->user->name
            ])
        ]);
    }

    /**
     * Fetch all empls.
     */
    public function all(Request $request)
    {
        if ($request->header('apikey') == 'vAWGg6KrAGsxuz9') {
            $employees = Employee::with('user.meta', 'position.position.department')->get()->map(fn ($e) => [
                'empl_id'       => $e->id,
                'user_id'       => $e->user_id,
                'username'      => $e->user->username,
                'email'         => $e->user->email_address,
                'password'      => $e->user->password,
                'name'          => $e->user->name,
                'avatar'        => $e->user->profile_avatar_path,
                'permanent_at'  => $e->permanent_at,
                'permanent_kd'  => $e->permanent_kd,
                'position'      => [
                    'pos_id'      => $e->position->position->id,
                    'name'        => $e->position->position->name,
                    'level'       => $e->position->position->level,
                    'department'  => [
                        'dept_id'   => $e->position->position->department->id,
                        'name'      => $e->position->position->department->name,
                    ]
                ]
            ]);

            return response()->success([
                'message' => 'Berikut adalah daftar semua karyawan yang ada.',
                'employees' => $employees
            ]);
        }
        return response()->fail([
            'message' => 'Anda tidak memiliki akses ke tautan tersebut.',
        ]);
    }

    public function get(Request $request)
    {
        if ($request->header('apikey') == 'vAWGg6KrAGsxuz9') {
            return response()->success([
                'message'   => 'Berikut adalah daftar semua karyawan yang ada.',
                'data'      => Employee::whereIn('id', json_decode($request->get('list')))->with('user.meta', 'position.position.department')->get()->map(fn ($e) => [
                    'user_id'   => $e->user_id,
                    'name'      => $e->user->name,
                    'username'  => $e->user->username,
                    'email'     => $e->user->email_address,
                    'password'  => $e->user->password,
                    'meta'      => [
                        'avatar'     => $e->user->profile_avatar_path,
                        'poss_id'    => $e->position->id,
                        'poss_name'  => $e->position->position->name,
                        'poss_level' => $e->position->position->level,
                        'dept_id'    => $e->position->position->department->id,
                        'dept_name'  => $e->position->position->department->name,
                    ],
                ])
            ]);
        }
    }

    public function salary(Request $request)
    {
        $salary = Employee::with([
            'salaryTemplate' => fn ($t) => $t->with(['items' => fn ($i) => $i->where('slip_az', 1)->where('ctg_az', 1)]),
            'lastSalaryTemplate' => fn ($t) => $t->with(['items' => fn ($i) => $i->where('slip_az', 1)->where('ctg_az', 1)])
        ])->find($request->get('empl'));

        if ($request->header('apikey') == 'vAWGg6KrAGsxuz9') {
            return response()->success([
                'message'   => 'Berikut adalah referensi gaji karyawan yang Anda cari.',
                'data'      => $salary
            ]);
        }
    }
}

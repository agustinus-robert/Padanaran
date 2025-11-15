<?php

namespace Modules\Portal\Http\Controllers\Overtime;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Portal\Http\Controllers\Controller;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee = $request->user()->employee;
        $start_at = $request->get('start_at', cmp_cutoff(0)->format('Y-m-d'));
        $end_at = $request->get('end_at', cmp_cutoff(1)->format('Y-m-d'));

        $overtimes = $employee->overtimes->filter(fn ($ov) => $ov->hasAllApprovableResultIn('APPROVE'))->unique('id')->map(function ($o) use ($start_at, $end_at) {
            $dates = $o->dates->map(
                fn ($date) => [
                    'date' => $date['d'],
                    'start_at' => Carbon::parse($date['d'] . ' ' . $date['t_s']),
                    'end_at' => Carbon::parse($date['d'] . ' ' . $date['t_e']),
                ],
            )->filter(fn ($x) => Carbon::parse($start_at)->lte($x['date'] . ' 00:00:00') && Carbon::parse($end_at)->gte($x['date'] . ' 23:59:59'));

            $date = $dates->first();

            $total_time = round($dates->map(fn ($dt) => $dt['start_at']->diffInMinutes($dt['end_at']))->sum() / 60, 2);

            return [
                'name' => $o->name,
                'desc' => $o->description,
                'date' => $date['date'] ?? '',
                'start_at' => isset($date['start_at']) ? Carbon::parse($date['start_at'])->format('H:i') : '',
                'end_at' => isset($date['end_at']) ? Carbon::parse($date['end_at'])->format('H:i') : '',
                'total_hour' => $total_time
            ];
        });

        $data = array_values(array_filter($overtimes->toArray(), fn ($f) => $f['date'] != null));

        return response()->json([
            'title' => ($title = 'Rekap lembur karyawan periode ' . Carbon::parse($start_at)->isoFormat('LL') . ' s.d. ' . Carbon::parse($end_at)->isoFormat('LL')),
            'employee' => $employee->user->name,
            'department' => $employee->position->position->department->name,
            'position' => $employee->position->position->name,
            'columns' => [
                'name' => 'Nama',
                'desc' => 'Keterangan',
                'date' => 'Tanggal',
                'start_at' => 'Jam mulai',
                'end_at' => 'Jam selesai',
                'total_hour' => 'Jumlah jam lembur',
            ],
            'overtimes' => $data,
        ]);
    }
}

<?php

namespace Modules\Portal\Http\Controllers\Vacation\Quota;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Core\Enums\PositionLevelEnum;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\Portal\Http\Controllers\Controller;

class ExcelController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user = $request->user();
		$employee = $user->employee->load('position.position.children.department');

		$employees = Employee::with(['user', 'contract.position.position', 'vacationQuotas' => fn ($quota) => $quota->with('vacations', 'category')->whenInYear($request->get('year', date('Y')))->when(!$request->quota, fn ($query) => $query->whereHas('category', fn ($ctg) => $ctg->whereIn('type', VacationTypeEnum::getVisibleOnly())))])
			->whereHas('contract.position.position', fn ($position) => $position->whereIn('id', $request->get('p', $employee->position->position->children->pluck('id'))))
			->search($request->get('search'))
			->whenPositionOfDepartment($employee->position->position->dept_id, $request->get('position'))
			->whenTrashed($request->get('trash'))
			->get()
			->sortBy('user.name')
			->values();

		return [
			'title' => ($title = 'Sisa kuota cuti/libur hari raya departemen'),
			'subtitle' => 'Diunduh pada ' . now()->isoFormat('LLLL'),
			'file' => Str::slug($title . '-' . time()),
			'columns' => [
				'index' => '#',
				'name' => 'Nama karyawan',
				'position' => 'Jabatan',
				'category_name' => 'Kategori cuti',
				'start_end_at' => 'Masa berlaku',
				'quota' => 'Kuota cuti',
				'used' => 'Dipakai',
				'cashable' => 'Kompensasi',
				'remain' => 'Sisa cuti',
			],
			'employees' => $employees->map(fn ($employee, $i) => [
				'index' => $i + 1,
				'name' => $employee->user->name,
				'position' => $employee->contract->position->position->name,
				'quotas' => $employee->vacationQuotas->map(function ($quota) {
					$dates = $quota->vacations->pluck('dates')->flatten(1);
					return [
						'category_name' => $quota->category->name,
						'quota' => $quota->quota,
						'start_end_at' => $quota->start_at->isoFormat('L') . ' - ' . ($quota->end_at?->isoFormat('L') ?: '∞'),
						'used' => ($used = $dates->filter(fn ($v) => empty($v['cashable']))->count()),
						'cashable' => ($cashable = $dates->filter(fn ($v) => isset($v['cashable']))->count()),
						'remain' => $quota->quota - $used - $cashable
					];
				}),
			])
		];
	}
}

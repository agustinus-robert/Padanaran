<?php

namespace Modules\Portal\Http\Controllers\Vacation;

use Illuminate\Http\Request;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyDepartment;
use Modules\HRMS\Models\Employee;
use Modules\Portal\Http\Controllers\Controller;

class QuotaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$user = $request->user();
		$employee = $user->employee->load('position.position.children.department');

		$departments = CompanyDepartment::visible()->with('positions')->whereId($employee->position->position->dept_id)->get();

		$employees = Employee::with(['user.meta', 'contract.position.position', 'vacationQuotas' => fn ($quota) => $quota->with('vacations', 'category')->whenInYear($request->get('year', date('Y')))->when(!$request->quota, fn ($query) => $query->whereHas('category', fn ($ctg) => $ctg->whereIn('type', VacationTypeEnum::getVisibleOnly())))])
			->whereHas('contract.position.position', fn ($position) => $position->whereIn('id', $request->get('p', $employee->position->position->children->pluck('id'))))
			->search($request->get('search'))
			->whenPositionOfDepartment($employee->position->position->dept_id, $request->get('position'))
			->whenTrashed($request->get('trash'))
			->paginate($request->get('limit', 10));

		return view('portal::vacation.quotas.index', compact('user', 'employee', 'employees', 'departments'));
	}
}

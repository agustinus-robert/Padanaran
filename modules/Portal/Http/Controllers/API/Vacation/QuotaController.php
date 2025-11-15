<?php

namespace Modules\Portal\Http\Controllers\API\Vacation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Models\CompanyVacationCategory;
use Modules\Portal\Http\Controllers\Controller;

class QuotaController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		$employee = $request->user()->employee;

		return response()->success([
			'message' => 'Berikut adalah daftar kuota cuti berdasarkan kueri Anda.',
			'quotas' => $employee->vacationQuotas()->with('category', 'vacations.approvables')->active()->get()
				->sortBy('category.type.value')->filter(fn ($quota) => $quota->category->type->quotaVisibility()),
		]);
	}
}

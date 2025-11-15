<?php

namespace Modules\Portal\Http\Controllers\Vacation;

use PDF;
use Str;
use Storage;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Docs\Models\Document;

class PrintController extends Controller
{
	/**
	 * Print the document
	 */
	public function index(EmployeeVacation $vacation)
	{
		$document = $vacation->firstOrCreateDocument(
			$title = 'Surat Permohonan Cuti - ' . $vacation->quota->employee->user->name . ' - ' . $vacation->created_at->getTimestamp(),
			$path = 'employee/vacations/' . Str::random(36) . '.pdf'
		);

		$document->sign($vacation->approvables->filter(
			fn ($a) =>
			$a->result == ApprovableResultEnum::APPROVE || $a->cancelable
		)->pluck('userable.employee.user_id')->prepend($vacation->quota->employee->user_id));

		$to = $vacation->approvables->sortByDesc('level')->first()?->userable ?: null;

		Storage::disk('docs')->put($document->path, PDF::loadView('portal::vacation.print.letter', compact('vacation', 'document', 'title', 'to'))->output());

		return $document->show();
	}
}

<?php

namespace Modules\Finance\Http\Controllers\Service\Loan;

use PDF;
use Str;
use Storage;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Finance\Http\Controllers\Controller;

class PrintController extends Controller
{
	/**
	 * Print the document
	 */
	public function index(EmployeeLoan $loan)
	{
		$document = $loan->firstOrCreateDocument(
			$title = 'Surat permohonan pinjaman - ' . $loan->employee->user->name . ' - ' . $loan->created_at->getTimestamp(),
			$path = 'employee/loan/submission/' . $loan->employee->user_id . '/' . Str::random(36) . '.pdf'
		);

		$document->sign($loan->approvables->filter(
			fn ($a) =>
			$a->result == ApprovableResultEnum::APPROVE || $a->cancelable
		)->pluck('userable.employee.user_id')->prepend($loan->employee->user_id));

		$to = $loan->approvables->sortByDesc('level')->first()?->userable ?: null;

		Storage::disk('docs')->put($document->path, PDF::loadView('portal::loan.print.letter', compact('loan', 'document', 'title', 'to'))->output());

		return $document->show();
	}
}

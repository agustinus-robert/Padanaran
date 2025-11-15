<?php

namespace Modules\Finance\Http\Controllers\Payment\Additional;

use Illuminate\Http\Request;
use PDF;
use Str;
use Storage;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Docs\Enums\DocumentTypeEnum;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Docs\Models\Document;
use Modules\HRMS\Models\EmployeeDataRecapitulation;

class PrintController extends Controller
{
	/**
	 * Print the document
	 */
	public function index(Request $request)
	{
		$recap = EmployeeDataRecapitulation::find($request->get('recap'));

		$document = Document::firstOrCreate([
			'modelable_type' => get_class($recap),
			'modelable_id' 	 => $recap->id
		], [
			'type' 	=> DocumentTypeEnum::SYSTEM,
			'label' => 'Slip honor kegiatan tambahan ' . $recap->employee->user->name . ' ' . strtotime($recap->created_at),
			'path' 	=> 'employee/payment/additional/' . Str::random(36) . '.pdf',
			'kd' 	=> Str::uuid(),
			'qr' 	=> Str::random(32)
		]);

		$title = 'Slip honor kegiatan tambahan - ' . $recap->employee->user->name;

		if ($recap) {
			$attachments['additional'] = $recap->employee->additional_overtimes()->whereIn('id', array_column($recap->result->payments, 'id'))->get()->map(fn ($p) => [
				'id' => $p->id,
				'name' => $p->type->label(),
				'description' => $p->description,
				'period' => $p->period,
				'amount' => collect($recap->result->payments)->firstWhere('id', $p->id)->dates
			]);
		}

		Storage::disk('docs')->put($document->path, PDF::setPaper('A5', 'landscape')->loadView('finance::payment.additional.show', compact('recap', 'document', 'title', 'attachments'))->output());

		return $document->show();
	}
}

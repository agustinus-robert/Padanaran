<?php

namespace Modules\Admin\Http\Controllers\Inventory\Lease;

use PDF;
use Str;
use Storage;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Core\Models\CompanyBorrow;
use Modules\Admin\Http\Controllers\Controller;

class PrintController extends Controller
{
	/**
	 * Print the document
	 */
	public function index(CompanyBorrow $manage)
	{
		$document = $manage->firstOrCreateDocument(
			$title = 'Tanda terima barang milik perusaaan - ' . $manage->receiver->name . ' - ' . $manage->created_at->getTimestamp(),
			$path  = 'employee/assets/' . Str::random(36) . '.pdf'
		);

		$document->sign($manage->approvables->filter(
			fn ($a) =>
			$a->result == ApprovableResultEnum::APPROVE
		)->pluck('userable.id')->prepend($manage->receiver->id));

		$signatures = [
			[
				'position' => 'Karyawan',
				'qr' => $document->signatures->firstWhere('user_id', $manage->receiver_id)?->qr,
				'name' => $manage->receiver->name,
			],
		];

		foreach ($manage->approvables as $approvable) {
			array_push($signatures, [
				'position' => 'Perusahaan',
				'qr' => $document->signatures->firstWhere('user_id', $approvable->userable->id)?->qr,
				'name' => $approvable->userable->name,
			]);
		}

		$pattern = array('#<p(.*?)>(.*?)</p>#is', '#<span (.*?)>(.*?)</span>#is');
		$replacement = array('$2<br/>', '$2');

		$text = preg_replace($pattern, $replacement, $manage->meta->clause);

		Storage::disk('docs')->put($document->path, PDF::loadView('asset::lease.manages.report', compact('manage', 'document', 'title', 'signatures', 'text'))->output());

		return $document->show();
	}
}

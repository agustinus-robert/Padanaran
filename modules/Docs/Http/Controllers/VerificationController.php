<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Docs\Http\Controllers\Controller;
use Modules\Docs\Models\Document;
use Modules\Docs\Models\DocumentSignature;

class VerificationController extends Controller
{
	/**
	 * Verify document
	 */
	public function index(Request $request, $qr = null)
	{
		$model = ($request->get('type') == 'signature' ? new DocumentSignature : new Document)->whereQr($qr)->first();

		return view('docs::verification.index', compact('model'));
	}
}
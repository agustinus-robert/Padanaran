<?php

namespace Modules\Academic\Http\Controllers\API\Invoice;
use Auth;
use Illuminate\Http\Request;
use Modules\Administration\Http\Controllers\Controller;
use Modules\Academic\Models\AcademicSubjectSchedule;
use Modules\Administration\Models\SchoolBillStudent;

class InvoicesControllerAPI extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Gunakan Bearer Token
    }

    /**
     * List buildings with pagination
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBillStudent::class);

        try {
            $trashed     = $request->get('trash', 0);
            $search      = $request->get('search', '');
            $smtId      = $request->get('smt_id');
            $batchId    = $request->get('batch_id');
            $limit       = $request->get('limit', 10);

            $acdmcInvoice = SchoolBillStudent::when($trashed, fn($query) => $query->onlyTrashed())
                ->when($smtId, fn($query) => $query->where('smt_id', $smtId))
                ->when($batchId, fn($query) => $query->where('batch_id', $batchId))
                ->paginate($limit);

            if ($acdmcInvoice->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada data untuk filter ini'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $acdmcSchedule
            ]);

        } catch (\Exception $e) {
            // Fallback kalau query error
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
}

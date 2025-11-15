<?php

namespace Modules\Poz\Http\Controllers\Schedule;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\Product;
use Illuminate\Http\Request;
use Modules\Poz\Models\Supplier;
use Modules\Poz\Models\Adjustment;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\Purchase;
use Modules\Poz\Models\Sale;
use Modules\Poz\Models\SaleDirect;
use Modules\Poz\Models\SupplierSchedule;
use DB;

class SupplierScheduleController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];

        $data['column'] = [
            dbuilder_table('time', 'Shift'),
            dbuilder_table('total_supplier', 'Jumlah Supplier', false, true),
            dbuilder_table('action', 'Aksi', false, false),
        ];

        $data['title'] = 'Supplier';

        return view('poz::schedule.supplier_schedule', $data);
    }

    public function show($supplier_schedule){
        $suppliers = Supplier::whereNull('deleted_at')->get();
        $products = Product::whereNull('deleted_at')->get();
        $prodSupp = SupplierSchedule::whereNull('deleted_at')->where('time', $supplier_schedule)->get();

        return view('poz::schedule.show', compact('suppliers', 'products', 'supplier_schedule', 'prodSupp'));
    }

    public function store(Request $request)
    {
        $time = strtolower($request->time);
        $schedules = $request->input('schedules', []);

        DB::beginTransaction();

        try {
            foreach ($schedules as $schedule) {
                $supplierId = $schedule['supplier_id'] ?? null;
                $productId = $schedule['product_id'] ?? null;

                if (!$supplierId || !$productId) {
                    continue;
                }

                SupplierSchedule::where('supplier_id', $supplierId)
                    ->where('product_id', $productId)
                    ->where('time', $time)
                    ->delete();

                SupplierSchedule::create([
                    'supplier_id' => $supplierId,
                    'product_id' => $productId,
                    'day'        => null,
                    'time'       => $time,
                ]);
            }

            DB::commit();

            return redirect(
                route('poz::schedule.supplier_schedule.index', [
                    'outlet' => request()->query('outlet', auth()->user()->current_outlet_id)
                ])
            )->with('msg-sukses', 'Shift berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->route('poz::schedule.supplier_schedule.index', [
                    'outlet' => request()->query('outlet', auth()->user()->current_outlet_id)
                ])->with('msg-error', 'Gagal menyimpan jadwal: ' . $e->getMessage());
        }
    }



   public function supplierScheduleTable(Request $request)
   {
        $outletId = $request->outlet;

        $timeLabels = [
            'morning' => 'Pagi',
            'afternoon' => 'Siang',
            'evening' => 'Sore',
        ];

        $data = collect($timeLabels)->map(function ($label, $key) use ($outletId) {
            $total = SupplierSchedule::where('time', $key)
                ->whereNull('deleted_at')
                ->whereHas('supplier.outlets', function ($query) use ($outletId) {
                    $query->where('outlet_id', $outletId);
                })
                ->distinct('supplier_id')
                ->count('supplier_id');

            return (object)[
                'time' => $label, // Untuk ditampilkan di kolom 'time'
                'time_key' => $key, // Untuk dikirim via URL
                'total_supplier' => $total,
            ];
        });

        return Table::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($outletId) {
                return '<a href="' . route('poz::schedule.supplier_schedule.show', [
                    'supplier_schedule' => $row->time_key,
                    'outlet' => $outletId,
                ]) . '" class="btn btn-sm btn-primary">Manage</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}

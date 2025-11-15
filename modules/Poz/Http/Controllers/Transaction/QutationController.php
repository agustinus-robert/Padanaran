<?php

namespace Modules\Poz\Http\Controllers\Transaction;

use Modules\Reference\Http\Controllers\Controller;
use Yajra\DataTables\DataTables as Table;
use Modules\Poz\Models\ProductQuotation;
use Modules\Poz\Models\ProductQuotationItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class QutationController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $data = [];
        // dbuilder_table untuk membuat generate table pada kolom header dan pemanggilan kolom database
        $data['column'] = [
            //DT_RowIndex usahakan false karena tidak ada secara fisik pada database
            dbuilder_table('reference', 'Referensi', false, false),
            dbuilder_table('outlet_name', 'Nama Outlet'),
            dbuilder_table('status', 'Status'),
            dbuilder_table('date', 'Tanggal Pengajuan'),
            dbuilder_table('action', 'Aksi')
        ];

        return view('poz::transaction.quotation.index', $data);
    }

    public function create(Request $request)
    {

        return view('poz::supplierz.quotation.index', [
            'action' => 'Create'
        ]);
    }

    public function show(ProductQuotation $qutation){
        $quotation = $qutation;
        return view('poz::transaction.quotation.show', compact('quotation'));
    }

    public function edit(Request $request)
    {

        return view('poz::supplierz.quotation.index', [
            'action' => 'Update'
        ]);
    }

    public function update(ProductQuotation $qutation, Request $request){
        $validated = $request->validate([
            'status_items' => 'array',
            'status_items.*' => 'in:2,3',
            'comments' => 'nullable|string',
        ]);


        try {
            DB::beginTransaction();

            $qutation->update([
                'status'   => 2,
                'comments' => $validated['comments'] ?? null,
            ]);

            if (!empty($validated['status_items'])) {
                foreach ($validated['status_items'] as $itemId => $status) {
                    $item = ProductQuotationItems::find($itemId);

                    if ($item && $item->quotation_id == $qutation->id) {
                        $item->update(['status' => $status]);
                    }
                }
            }

            DB::commit();

            return redirect()
                ->back()
                ->with('msg-sukses', 'Status quotation berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal update quotation', [
                'quotation_id' => $quotation->id,
                'error'        => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('msg-error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy(ProductQuotation $quotation)
    {
        $quotation->delete(); // Melakukan soft delete

        return redirect()->back()->with('msg-sukses', 'Status quotation berhasil dihapus!');
    }

    public function send(ProductQuotation $quotation){
        $quotation->update([
            'status' => 1
        ]);

        return redirect()->back()->with('msg-sukses', 'Status quotation berhasil diperbarui!');
    }

    public function quotationTable(Request $request)
    {
        $outletId = $request->outlet;
        $quotation = ProductQuotation::with('outlets')
        ->whereNull('deleted_at')
        ->whereHas('outlets', function ($query) use ($outletId) {
            $query->where('outlet_id', $outletId);
        });

        if (!empty($search = $request->search)) {
            $quotation->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%{$search}%");
            });
        }

        if (!empty($order = $request->filter)) {
            if ($order === 'new') {
                $quotation->orderBy('id', 'desc');
            } elseif ($order === 'old') {
                $quotation->orderBy('id', 'asc');
            }
        }

        return Table::of($quotation)
            ->addIndexColumn()
            ->addColumn('reference', function ($row) {
               return $row->reference;
            })
            ->addColumn('outlet_name', function ($row) {
                return $row->outlets->first()->name;
            })
            ->addColumn('status', function($row){
                if($row->status == 1){
                    return '<span class="badge bg-warning">Menunggu</span>';
                } else if($row->status == 2){
                    return '<span class="badge bg-success">Diterima</span>';
                } else if($row->status == 3) {
                    return '<span class="badge bg-danger">Ditolak</span>';
                } else {
                    return '<span class="badge bg-secondary">Belum Dikirim</span>';
                }
            })
            ->addColumn('date', function($row){
                 return \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y H:i');
            })
            ->addColumn('action', function ($row) {
                $template = '';
                //
                $template .= view('poz::layouts_master.component.button_show', array('id' => $row->id, 'show' => route('poz::transaction.qutation.show', ['qutation' => $row->id])))->render();
                return $template;
            })
            ->rawColumns(['status', 'action'])->make(true);
    }
}

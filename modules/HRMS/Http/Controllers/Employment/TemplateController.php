<?php

namespace Modules\HRMS\Http\Controllers\Employment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HRMS\Exports\ExportEmployee;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Http\Controllers\Controller;
use Modules\HRMS\Imports\ImportEmployee;

class TemplateController extends Controller
{

    /**
     * download template excel.
     */
    public function download(Request $request)
    {
        $this->authorize('store', Employee::class);
        $employees = Employee::with('user.meta', 'contract.positions.position')
            ->has('contract')
            ->search($request->get('search'))
            ->whenTrashed($request->get('trash'))
            ->get();

        return Excel::download(new ExportEmployee($employees), 'Template data karyawan.xlsx');
    }

    /**
     * Upload to database via excel.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'file|mimes:xlsx|max:2048',
        ], [
            'file.required' => 'File is required.',
            'file.mimes' => 'The file must be an Excel file (.xls or .xlsx).',
            'file.max' => 'The file size must not exceed 2 MB.',
        ]);

        DB::beginTransaction();

        try {
            $import = new ImportEmployee();
            $import->import($request->file('file'));
            Log::info('Berhasil import data dari excel', ['success' => 'data']);
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = '';
            foreach ($failures as $failure) {
                $failure->row();
                $failure->attribute();
                $failure->errors();
                $failure->values();
                $message .= '<br>' . $failure->values()['nama'] . ' : ' . $failure->errors()[0];
            }
            DB::rollBack();
            Log::error('Terjadi kegagalan', ['data' => $message]);
            return redirect()->back()->with('danger', 'Error! ' . $message);
        }
        return redirect()->back()->with('success', 'Berhasil impor data!');
    }
}

<?php

namespace Modules\Portal\Http\Controllers\Tax;

use Auth;
use Storage;
use PDF;
use Arr;
use Str;
use Illuminate\Http\Request;
use Modules\Account\Enums\MariageEnum;
use Modules\Docs\Enums\DocumentTypeEnum;
use Modules\Docs\Models\Document;
use Modules\HRMS\Models\EmployeeTax;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Http\Requests\Tax\StoreRequest;

class PayerController extends Controller
{
    /**
     * Define the primary meta keys for resource
     */
    private $metaKeys = [
        'tax_number', 'tax_address', 'profile_nik', 'profile_mariage', 'profile_child', 'address_address', 'tax_file',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('portal::tax.payers.index', [
            'user'       => ($user = $request->user()),
            'employee'   => ($employee = $user->employee),
            'taxs'       => $employee->taxs()->whereNotNull('released_at')->paginate($request->get('limit', 10)),
            'taxs_count' => $employee->taxs()->count(),
            'mariages'   => MariageEnum::cases()
        ]);
    }

    public function store(StoreRequest $request)
    {
        $user = $request->user();
        $user->fill(Arr::only($request->transformed()->toArray(), ['name']));

        if ($user->save()) {
            // Update data
            $user->setManyMeta(Arr::only($request->transformed()->toArray(), $this->metaKeys));
            $user->log('memperbarui informasi wajib pajak ' . $user->name . ' <strong>[ID: ' . $user->id . ']</strong>', User::class, $user->id);

            // Create document
            $title = 'Formulir data wajib pajak orang pribadi - ' . $user->name . ' - tahun ' . date('Y');
            $path  = 'employee/taxs/forms' . Str::random(36) . '.pdf';

            $document = Document::firstOrCreate(
                ['modelable_type' => $user::class, 'modelable_id' => $user->id, 'label' => $title ?: Str::random(36)],
                ['type' => DocumentTypeEnum::TAX, 'path' => $path, 'kd' => Str::uuid(), 'qr' => Str::random(32)]
            );

            if ($document) {
                $document->sign(array($user->id));
                $signatures = [
                    [
                        'position'  => 'Karyawan',
                        'qr'        => $document->signatures->firstWhere('user_id', $user->id)?->qr,
                        'name'      => $user->name,
                    ],
                ];
                Storage::disk('docs')->put($document->path, PDF::loadView('portal::tax.payers.report', compact('user', 'document', 'signatures', 'title'))->output());
            }

            return redirect()->next()->with('success', 'Informasi wajib pajak <strong>' . $user->name . '</strong> berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    public function print(Request $request)
    {
        $document = Document::whereType(DocumentTypeEnum::TAX)->whereModelableId($user = $request->user()->id)->latest()->first();
        if ($document) {
            return $document->show();
        }
        return abort(404);
    }
}

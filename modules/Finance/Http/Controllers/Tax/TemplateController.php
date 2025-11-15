<?php

namespace Modules\Finance\Http\Controllers\Tax;

use Illuminate\Http\Request;
use App\Models\Setting;
use Modules\Finance\Http\Requests\Tax\Template\StoreRequest;
use Modules\Finance\Http\Requests\Tax\Template\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\CompanySalarySlip;
use Modules\Core\Models\CompanySalarySlipComponent;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = 'cmp_pph_components';

        return view('finance::tax.templates.index', [
            'templates'      => Setting::where('key', $search)->paginate($request->get('limit', 10)),
            'template_count' => Setting::where('key', $search)->count(),
        ]);
    }

    /* *
     * Create new resource
     */
    public function create()
    {
        return view('finance::tax.templates.create', [
            'slips' => CompanySalarySlip::where('grade_id', userGrades())->get(),
            'items' => CompanySalarySlipComponent::where('grade_id', userGrades())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($request->input('as_template')) {
            setting_set($request->transformed()->toArray()['key'], $request->transformed()->toArray()['components']);
            return redirect()->next()->with('success', 'Template <strong>' . $request->input('key') . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $template)
    {
        return view('finance::tax.templates.show', [
            'template'   => $template,
            'components' => collect($template['value']),
            'slips'      => CompanySalarySlip::with('categories.components')->where('grade_id', userGrades())->get(),
            'items'      => CompanySalarySlipComponent::where('grade_id', userGrades())->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Setting $template, UpdateRequest $request)
    {
        if ($template) {
            if ($request->input('as_template')) {
                setting_set('cmp_pph_components', $request->transformed()->toArray()['components']);
            }
            return redirect()->next()->with('success', 'Perubahan template berhasil disimpan.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $template)
    {
        return redirect()->fail();
    }
}

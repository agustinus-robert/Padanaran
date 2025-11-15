<?php

namespace Modules\Admin\Http\Controllers\System;

use App\Models\Contract;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\System\Contract\StoreRequest;
use Modules\Admin\Http\Requests\System\Contract\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Repositories\CompanyContractRepository;

class ContractController extends Controller
{
    use CompanyContractRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Contract::class);

        $contracts = Contract::whenTrashed($request->get('trash'))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $contracts_count = Contract::count();

        return view('admin::system.contracts.index', compact('contracts', 'contracts_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', Contract::class);

        $contracts = Contract::all();

        return view('admin::system.contracts.create', compact('contracts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($contract = $this->storeCompanyContract($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Departemen dengan nama <strong>' . $contract->name . ' (' . $contract->kd . ')</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $this->authorize('update', $contract);

        $contracts = Contract::all();

        return view('admin::system.contracts.show', compact('contracts', 'contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Contract $contract, UpdateRequest $request)
    {
        if ($contract = $this->updateCompanyContract($contract, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Kontrak dengan nama <strong>' . $contract->name . ' (' . $contract->kd . ')</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $this->authorize('destroy', $contract);

        if ($contract = $this->destroyCompanyContract($contract)) {
            return redirect()->next()->with('success', 'Kontrak dengan nama <strong>' . $contract->name . ' (' . $contract->kd . ')</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Contract $contract)
    {
        $this->authorize('restore', $contract);

        if ($contract = $this->restoreCompanyContract($contract)) {
            return redirect()->next()->with('success', 'Kontrak dengan nama <strong>' . $contract->name . ' (' . $contract->kd . ')</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}

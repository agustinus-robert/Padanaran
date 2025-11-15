<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Inventory\Proposal\StoreRequest;
use Modules\Admin\Http\Requests\Inventory\Proposal\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Admin\Models\Procurement;
use Modules\Account\Models\EmployeePosition;
use Modules\Admin\Enums\PlaceableTypeEnum;
use App\Enums\ApprovableResultEnum;
use Modules\Core\Repositories\CompanyInventoryProposalRepository;
use Modules\Asset\Notifications\Inventory\Proposal\SubmissionNotification;

class ProcurementController extends Controller
{
    // use CompanyInventoryProposalRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Procurement::class);

        return view('admin::inventories.procurements.index', [
            'procurements' => Procurement::whenTrashed($request->get('trash'))
                ->search($request->get('search'))
                ->orderBy('name')
                ->paginate($request->get('limit', 10))
        ]);
    }

    /**
     * Create buildings
     * */
    public function create(Request $request)
    {
        return view('admin::inventories.procurements.create', [
            'placeables' => PlaceableTypeEnum::cases(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $employee = $request->user()->employee;

        if ($proposal = $this->storeCompanyInventoryProposal($request->transformed()->toArray())) {

            foreach (config('modules.core.features.assets.inventories.approvable_steps') as $model) {
                if ($model['type'] == 'parent_position_level') {
                    if ($position = $employee->position->position->parents->firstWhere('level.value', $model['value'])?->employeePositions()->active()->first()) {
                        $proposal->createApprovable($position);
                    }
                }
                if ($model['type'] == 'employee_position_by_kd') {
                    if ($approver = EmployeePosition::active()->whereHas('position', fn ($position) => $position->whereIn('kd', $model['value']))->first()) {
                        $proposal->createApprovable($approver);
                    }
                }
            }

            // Handle notifications
            if ($approvable = $proposal->approvables()->orderBy('level')->first()) {
                $approvable->userable->getUser()->notify(new SubmissionNotification($proposal));
            }

            return redirect()->next()->with('success', 'Proposal <strong>' . $proposal->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Edit the specified resource.
     */
    public function edit(CompanyInventoryProposal $proposal)
    {
        $this->authorize('update', $proposal);

        return view('asset::inventories.proposals.edit', [
            'placeables' => PlaceableTypeEnum::cases(),
            'proposal'   => $proposal->load('items'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyInventoryProposal $proposal, Request $request)
    {
        $this->authorize('update', $proposal);

        return view('asset::inventories.proposals.show', [
            'employee'   => $request->user()->employee,
            'proposal'   => $proposal->load('items'),
            'placeables' => PlaceableTypeEnum::cases(),
            'results'    => ApprovableResultEnum::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyInventoryProposal $proposal, UpdateRequest $request)
    {
        $this->authorize('update', $proposal);

        if ($proposal = $this->updateCompanyInventoryProposal($proposal, $request->transformed()->toArray())) {

            return redirect()->next()->with('success', 'Proposal dengan nama <strong>' . $proposal->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyInventoryProposal $proposal)
    {
        $this->authorize('destroy', $proposal);

        if ($proposal = $this->destroyCompanyInventoryProposal($proposal)) {

            return redirect()->next()->with('success', 'Proposal dengan nama <strong>' . $proposal->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(CompanyInventoryProposal $proposal)
    {
        $this->authorize('restore', $proposal);

        if ($proposal = $this->restoreCompanyInventoryProposal($proposal)) {

            return redirect()->next()->with('success', 'Proposal dengan nama <strong>' . $proposal->name . '</strong> telah berhasil dipulihkan.');
        }
        return redirect()->fail();
    }
}

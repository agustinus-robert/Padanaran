<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Inventory\Proposal\StoreRequest;
use Modules\Admin\Http\Requests\Inventory\Proposal\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Account\Models\EmployeePosition;
use Modules\Admin\Enums\PlaceableTypeEnum;
use App\Enums\ApprovableResultEnum;
use Modules\Admin\Models\Building;
use Modules\Asset\Notifications\Inventory\Proposal\SubmissionNotification;

class BuildingController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Land::class);
        
        return view('admin::inventories.building.index', [
            'building_count' => Building::count()
        ]);
    }
}
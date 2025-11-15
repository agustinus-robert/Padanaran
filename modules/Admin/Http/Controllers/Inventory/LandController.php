<?php

namespace Modules\Admin\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\Inventory\Proposal\StoreRequest;
use Modules\Admin\Http\Requests\Inventory\Proposal\UpdateRequest;
use Modules\Admin\Http\Controllers\Controller;
use Modules\Account\Models\EmployeePosition;
use Modules\Admin\Enums\PlaceableTypeEnum;
use App\Enums\ApprovableResultEnum;
use Modules\Admin\Models\Land;
use Modules\Asset\Notifications\Inventory\Proposal\SubmissionNotification;

class LandController extends Controller
{
	 public function index(Request $request)
    {
        $this->authorize('access', Land::class);
        
        return view('admin::inventories.land.index', [
            'land_count' => Land::count()
        ]);
    }
}
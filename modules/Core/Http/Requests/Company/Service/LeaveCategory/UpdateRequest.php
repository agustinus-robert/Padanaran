<?php

namespace Modules\Core\Http\Requests\Company\Service\LeaveCategory;

use Modules\Core\Models\CompanyLeaveCategory;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->category);
    }
}
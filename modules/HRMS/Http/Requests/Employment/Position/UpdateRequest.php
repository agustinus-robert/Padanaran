<?php

namespace Modules\HRMS\Http\Requests\Employment\Position;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->position);
    }
}

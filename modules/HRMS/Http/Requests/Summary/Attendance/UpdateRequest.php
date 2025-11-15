<?php

namespace Modules\HRMS\Http\Requests\Summary\Attendance;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }
}

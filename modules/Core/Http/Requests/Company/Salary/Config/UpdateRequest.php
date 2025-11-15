<?php

namespace Modules\Core\Http\Requests\Company\Salary\Config;


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

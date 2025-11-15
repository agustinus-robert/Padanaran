<?php

namespace Modules\Asset\Http\Requests\Inventory\Control\Sale;

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

<?php

namespace Modules\Core\Http\Requests\Company\Asset\Room;

use Modules\Core\Models\CompanyBuilding;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->room);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'building_id'   => 'required|numeric|exists:' . (new CompanyBuilding)->getTable() . ',id',
            'name'          => 'required|max:191|string',
            'capacity'      => 'nullable|numeric',
            'length'        => 'nullable|numeric',
            'width'         => 'nullable|numeric',
            'rentable'      => 'nullable|numeric',
        ];
    }
}

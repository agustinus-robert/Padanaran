<?php

namespace Modules\Admin\Http\Requests\System\Departement;

use App\Models\Departement;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->department);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'                => 'required|max:191|string|unique:' . (new Departement())->getTable() . ',kd,' . $this->department->id,
            'name'              => 'required|max:191|string',
            'description'       => 'nullable|max:500|string',
            'parent_id'         => 'nullable|exists:' . (new Departement)->getTable() . ',id',
            'is_visible'        => 'boolean'
        ];
    }
}

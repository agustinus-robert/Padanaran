<?php

namespace Modules\Core\Http\Requests\Company\Role;

use Modules\Core\Models\CompanyRole;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->role);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'      => 'required|string|max:191|unique:'.(new CompanyRole)->getTable().',kd,'.$this->role->id,
            'name'      => 'nullable|string|max:191',
        ];
    }
}
<?php

namespace Modules\Admin\Http\Requests\System\Position;

use App\Models\Departement;
use App\Models\Position;

class UpdateRequest extends StoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->position);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $departments = Departement::all();
        $positions = Position::all();

        return [
            'dept_id'           => 'required|in:' . $departments->pluck('id')->join(','),
            'kd'                => 'required|max:191|string|notin:' . $positions->whereNotIn('id', $this->position->id)->pluck('kd')->join(','),
            'name'              => 'required|max:191|string',
            'description'       => 'nullable|max:500|string',
            'parents.*'         => 'nullable|in:' . $positions->pluck('id')->join(','),
            'children.*'        => 'nullable|in:' . $positions->pluck('id')->join(','),
            'level'             => 'required|numeric|min:1|max:10',
            'is_visible'        => 'boolean'
        ];
    }
}

<?php

namespace Modules\Admin\Http\Requests\System\Departement;

use App\Http\Requests\FormRequest;
use App\Models\Departement;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Departement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'                => 'required|max:191|string|unique:' . (new Departement)->getTable() . ',kd',
            'name'              => 'required|max:191|string',
            'description'       => 'nullable|max:500|string',
            'parent_id'         => 'nullable|exists:' . (new Departement)->getTable() . ',id',
            'is_visible'        => 'boolean'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'                => 'kode departemen',
            'name'              => 'nama departemen',
            'description'       => 'deskripsi',
            'parent_id'         => 'departemen atasan',
            'is_visible'        => 'visibilitas'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->only([
            'kd', 'name', 'description', 'parent_id', 'is_visible'
        ]);
    }
}

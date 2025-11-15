<?php

namespace Modules\Admin\Http\Requests\System\Role;

use App\Http\Requests\FormRequest;
use App\Models\Role;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Role::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'        => 'required|min:2|max:191|regex:/^[a-z\d._]{2,20}$/|unique:' . (new Role)->getTable() . ',kd',
            'name'      => 'required|max:191|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd' => 'kode peran',
            'name' => 'nama peran'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages()
    {
        return [
            'kd.unique' => 'Isian :attribute sudah digunakan sebelumnya, silahkan gunakan :attribute lainnya.'
        ];
    }
}

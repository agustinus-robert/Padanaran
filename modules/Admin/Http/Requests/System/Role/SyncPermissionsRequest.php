<?php

namespace Modules\Admin\Http\Requests\System\Role;

use App\Http\Requests\FormRequest;

class SyncPermissionsRequest extends FormRequest
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
            'permissions.*' => 'nullable|exists:app_permissions,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'permissions.*' => 'hak akses'
        ];
    }
}

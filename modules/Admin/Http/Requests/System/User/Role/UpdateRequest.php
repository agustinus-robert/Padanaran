<?php

namespace Modules\Admin\Http\Requests\System\User\Role;

use App\Http\Requests\FormRequest;
use App\Models\Role;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'role_id' => 'required|exists:' . (new Role())->getTable() . ',id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'role_id'     => 'peran',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'role_id' => $this->input('role_id'),
        ];
    }
}

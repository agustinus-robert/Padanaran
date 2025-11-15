<?php

namespace Modules\Admin\Http\Requests\System\User\Email;

use Modules\Account\Http\Requests\User\Email\UpdateRequest as FormRequest;

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
            'email_address'     => 'required|email|max:191|unique:users,email_address,' . $this->user->id . ',id',
        ];
    }
}

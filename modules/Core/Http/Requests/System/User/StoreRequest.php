<?php

namespace Modules\Core\Http\Requests\System\User;

use App\Http\Requests\FormRequest;
use Modules\Account\Models\User;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'          => 'required|max:191|string',
            'username'      => 'required|min:4|max:191|regex:/^[a-z\d.]{4,20}$/|unique:'.(new User())->getTable().',username',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name' => 'nama lengkap',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages()
    {
        return [
            'username.unique' => 'Isian :attribute sudah digunakan oleh orang lain, silahkan gunakan :attribute lainnya.'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform ()
    {
        $this->merge(['password' => substr(str_shuffle('0123456789ABCDEF'), 0, 6)]);

        return $this->only('name', 'username', 'password');
    }
}
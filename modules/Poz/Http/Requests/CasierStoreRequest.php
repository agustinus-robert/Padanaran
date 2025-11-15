<?php

namespace Modules\Poz\Http\Requests;

use Auth;
use App\Http\Requests\FormRequest;


class CasierStoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Inquiry::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'form.name' => 'required',
            'form.username' => 'required',
            'form.password' => 'required',
            'form.email_address' => 'required',
            'form.outlet_id' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.name' => 'Nama Kasir',
            'form.username' => 'Username Kasir',
            'form.password' => 'Password Kasir',
            'form.email_address' => 'Email Kasir',
            'form.outlet_id' => 'Outlet Kasir'
        ];
    }

    public function messages(): array
    {
        return [
            'form.name.required' => ':attribute harus diisi',
            'form.username.required' => ':attribute harus diisi',
            'form.password.required' => ':attribute harus diisi',
            'form.email_address.required' => ':attribute harus diisi',
            'form.outlet_id.required' => ':attribute harus diisi',
        ];
    }
}

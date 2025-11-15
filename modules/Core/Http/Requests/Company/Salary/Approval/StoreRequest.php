<?php

namespace Modules\Core\Http\Requests\Company\Salary\Approval;

use App\Http\Requests\FormRequest;
use Modules\Account\Models\User;
use Modules\Core\Models\CompanyPayrollSetting;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyPayrollSetting::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'key'       => 'required|string|max: 191',
            'az'        => 'required|numeric',
            'employee'  => 'required|exists:' . (new User())->getTable() . ',id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'key'       => 'slip',
            'az'        => 'urutan',
            'employee'  => 'nama karyawan',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('key', 'az'),
            'meta' => [
                'model' => User::class,
                'methods' => 'find',
                'prop' => (int) $this->input('employee'),
                'get' => 'id',
            ]
        ];
    }
}

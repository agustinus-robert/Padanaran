<?php

namespace Modules\Core\Http\Requests\Company\Salary\Config;

use App\Http\Requests\FormRequest;
use Modules\Account\Models\User;
use Modules\Core\Enums\PayrollSettingEnum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        if ($this->input('active') == PayrollSettingEnum::APPROVABLE->value) {
            foreach ($this->input('approvable_step') as $key => $value) {
                $data[$key]['model'] = User::class;
                $data[$key]['methods'] = 'find';
                $data[$key]['prop'] = (int) $value['user_id'];
                $data[$key]['get'] = 'id';
                $data[$key]['type'] = $this->input('active');
                $data[$key]['az'] = $value['az'];
            }

            return [
                'key' => $this->input('key'),
                'az' => $this->input('active'),
                'meta' => [
                    'approvable_step' => $data
                ]
            ];
        }

        if ($this->input('active') == PayrollSettingEnum::FORMULA->value) {
            $data = [
                'type' => $this->input('active'),
                'config' => '1',
                'default_component' => $this->input('default_component'),
                'component' => $this->input('component'),
                'calculation' => $this->input('calculation'),
            ];

            return [
                'key' => $this->input('key'),
                'az' => $this->input('active'),
                'meta' => $data
            ];
        }
    }
}

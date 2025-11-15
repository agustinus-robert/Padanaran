<?php

namespace Modules\Finance\Http\Requests\Payroll\Calculation;

use App\Http\Requests\FormRequest;

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
     * Map to float values.
     */
    public function mapFloat(array $items)
    {
        foreach ($items as $key => $item) {
            $result[$key] = is_array($item) ? $this->mapFloat($item) : (!in_array($key, ['slip', 'ctg', 'name']) ? (float) $item : $item);
        }
        return $result ?? [];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            ...$this->only('name', 'start_at', 'end_at', 'amount', 'description'),
            'components' => (array) $this->mapFloat($this->input('components'))
        ];
    }
}

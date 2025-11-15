<?php

namespace Modules\HRMS\Http\Requests\Service\Leave\Approvable;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\ApprovableResultEnum;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return isset($this->user()->employee);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'result.*'            => ['required', new Enum(ApprovableResultEnum::class)],
            'reason.*'            => Rule::requiredIf(ApprovableResultEnum::tryFrom($this->input('result.'.$this->approvable->id))->reasonRequirement())
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'result.*'            => 'status',
            'reason.*'            => 'alasan'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return [
            'result' => $this->input('result.'.$this->approvable->id),
            'reason' => $this->input('reason.'.$this->approvable->id)
        ];
    }
}
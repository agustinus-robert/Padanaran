<?php

namespace Modules\HRMS\Http\Requests\Employment\Contract;

use App\Http\Requests\FormRequest;

class WorkdaysUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->contract);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'worktimes_default.*.*.*'  => 'nullable|date_format:H:i',
            'worktimes_default.*.*.*'  => 'nullable|date_format:H:i'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform ()
    {
        return $this->validated();
    }
}
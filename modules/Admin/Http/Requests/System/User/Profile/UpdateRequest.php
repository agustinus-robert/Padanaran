<?php

namespace Modules\Admin\Http\Requests\System\User\Profile;

use Illuminate\Validation\Rules\Enum;
use Modules\Account\Enums\SexEnum;
use Modules\Account\Enums\BloodEnum;
use Modules\Account\Enums\ReligionEnum;
use Modules\Account\Http\Requests\User\Profile\UpdateRequest as FormRequest;

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
            'profile_prefix'    => 'nullable|string|max:191',
            'name'              => 'required|string|max:191',
            'profile_suffix'    => 'nullable|string|max:191',
            'profile_sex'       => ['nullable', new Enum(SexEnum::class)],
            'profile_pob'       => 'nullable|string|max:191',
            'profile_dob'       => 'nullable|string|date_format:Y-m-d',
            'profile_blood'     => ['nullable', new Enum(BloodEnum::class)],
            'profile_religion'  => ['nullable', new Enum(ReligionEnum::class)]
        ];
    }
}

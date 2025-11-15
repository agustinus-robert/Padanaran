<?php

namespace Modules\Admin\Http\Requests\System\User\Phone;

use App\Rules\UniqueMetaValue;
use Modules\Reference\Models\Country;
use Modules\Account\Models\User;
use Modules\Account\Http\Requests\User\Phone\UpdateRequest as FormRequest;

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
            'phone_code'     => ['required', 'in:' . Country::select('phones', 'code')->get()->pluck('phones', 'code')->flatten()->join(',')],
            'phone_number'   => ['required', 'numeric', new UniqueMetaValue(User::class, 'phone_number', $this->user->getMeta('phone_number'))],
            'phone_whatsapp' => 'boolean'
        ];
    }
}

<?php

namespace Modules\Core\Http\Requests\Company\Moment;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\MomentTypeEnum;
use Modules\Core\Models\CompanyMoment;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyMoment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'type'                  => ['required', new Enum(MomentTypeEnum::class)],
            'date'                  => 'required|date',
            'name'                  => 'required|max:191|string',
            'is_holiday'            => 'boolean',
            'meta'                  => 'nullable'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'type'                  => 'tipe hari libur',
            'date'                  => 'tanggal',
            'name'                  => 'nama hari libur',
            'is_holiday'            => 'status',
            'meta'                  => 'nullable'
        ];
    }

    /**
     * Transform request data
     */
    public function transform()
    {
        $data = $this->only('type', 'date', 'name');

        $data['is_holiday'] = (bool) $this->input('is_holiday');
        $data['meta'] = [
            'religion' => $this->input('religion')
        ];

        return $data;
    }
}

<?php

namespace Modules\Core\Http\Requests\Company\Service\VacationCategory;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\VacationTypeEnum;
use Modules\Core\Models\CompanyVacationCategory;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyVacationCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'                  => 'required|max:191|string',
            'type'                  => ['nullable', new Enum(VacationTypeEnum::class)],
            'quota'                 => 'nullable|numeric|max:366',
            'fields'                => 'required|in:options,range',
            'as_freelance'          => 'nullable|boolean'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'                  => 'nama kategori',
            'type'                  => 'tipe cuti',
            'quota'                 => 'kuota',
            'fields'                => 'jenis inputan',
            'as_freelance'          => 'sebagai freelance'
        ];
    }

    /**
     * Transform request data
     */
    public function transform()
    {
        return [
            'name' => $this->input('name'),
            'type' => $this->input('type'),
            'meta' => array_filter([
                'quota' => (int) $this->input('quota'),
                'fields' => $this->input('fields'),
                'as_freelance' => $this->has('as_freelance'),
            ])
        ];
    }
}
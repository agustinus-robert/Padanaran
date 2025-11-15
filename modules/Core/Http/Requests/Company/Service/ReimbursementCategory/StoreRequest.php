<?php

namespace Modules\Core\Http\Requests\Company\Service\ReimbursementCategory;

use Modules\Core\Models\CompanyReimbursementCategory;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\ReimbursementMethodEnum;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyReimbursementCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'                  => 'required|max:191|string',
            'limit'                 => 'nullable|numeric',
            'method'                => 'required',
            'method.*'              => ['nullable', new Enum(ReimbursementMethodEnum::class)],
            'hide_limit'            => 'boolean',
            'only_permanent_empl'   => 'boolean'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'                  => 'nama kategori',
            'limit'                 => 'limit',
            'method'                => 'metode',
            'hide_limit'            => 'visibilitas limit',
            'only_permanent_empl'   => 'ketersediaan untuk karyawan tetap'
        ];
    }

    /**
     * Transform request data
     */
    public function transform()
    {
        return [
            'name' => $this->input('name'),
            'limit' => (float) $this->input('limit'),
            'meta' => array_filter([
                'hide_limit' => (bool) $this->input('hide_limit'),
                'only_permanent_empl' => (bool) $this->input('only_permanent_empl'),
                'method' => array_map(fn ($v) => (int) $v, $this->input('method', []))
            ])
        ];
    }
}

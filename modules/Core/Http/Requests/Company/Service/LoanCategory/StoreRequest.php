<?php

namespace Modules\Core\Http\Requests\Company\Service\LoanCategory;

use Modules\Core\Models\CompanyLoanCategory;
use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyLoanCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'        => 'required|max:191|string',
            'type'        => 'required|string',
            'description' => 'nullable|string',
            'interest_id' => 'nullable|numeric',
            'meta'        => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'        => 'kategori',
            'description' => 'keterangan',
            'type'        => 'jenis',
            'interest_id' => 'bunga atas',
            'meta'        => 'meta',
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
            'description' => $this->input('description'),
            'interest_id' => $this->input('interest_id'),
            'meta' => array_filter([
                'az' => (int) $this->input('az'),
                'visible' => (bool) $this->input('visible'),
                'tenor' => (int) $this->input('tenor'),
                'interest' => $this->input('interest'),
                'file' => (int) $this->input('file'),
                'divider' => (int) $this->input('divider'),
                'only_permanent_empl' => (bool) $this->input('only_permanent_empl'),
                'multiplied_by_tenor' => (bool) $this->input('multiplied_by_tenor'),
            ])
        ];
    }
}

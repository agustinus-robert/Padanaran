<?php

namespace Modules\Core\Http\Requests\Company\Service\OutworkCategory;

use Modules\Core\Models\CompanyLeaveCategory;
use App\Http\Requests\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', CompanyLeaveCategory::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $categories = CompanyLeaveCategory::whereNull('parent_id')->get('id');

        return [
            'name'        => 'required|max:191|string',
            'description' => 'required|max:191|string',
            'price'       => 'required|numeric',
            'meta'        => 'nullable',
            'in_working_hours_price' => 'nullable|numeric',
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
            'price'       => 'tarif',
            'meta'        => 'meta',
            'in_working_hours_price' => 'tarif',
        ];
    }

    /**
     * Transform request data
     */
    public function transform()
    {
        return [
            'name' => $this->input('name'),
            'price' => $this->input('price'),
            'description' => $this->input('description'),
            'meta' => array_filter([
                'prepareable' => (bool) $this->input('meta.prepareable'),
                'fixed' => (bool) $this->input('meta.fixed'),
                'in_working_hours_price' => (int) $this->input('in_working_hours_price'),
            ])
        ];
    }
}

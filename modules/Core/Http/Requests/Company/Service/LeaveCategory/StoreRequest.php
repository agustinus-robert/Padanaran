<?php

namespace Modules\Core\Http\Requests\Company\Service\LeaveCategory;

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
            'name'                  => 'required|max:191|string',
            'parent_id'             => 'nullable|in:'.$categories->pluck('id')->join(','),
            'quota'                 => 'nullable|numeric|max:366',
            'time_input'                => 'nullable|in:start_to_end,start_only',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'                  => 'nama kategori',
            'parent_id'             => 'kategori parent',
            'quota'                 => 'kuota',
            'time_input'            => 'inputan waktu'
        ];
    }

    /**
     * Transform request data
     */
    public function transform()
    {
        return [
            'name' => $this->input('name'),
            'parent_id' => $this->input('parent_id'),
            'meta' => array_filter([
                'quota' => (int) $this->input('quota'),
                'time_input' => $this->input('time_input')
            ])
        ];
    }
}
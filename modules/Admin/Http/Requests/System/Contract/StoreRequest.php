<?php

namespace Modules\Admin\Http\Requests\System\Contract;

use App\Http\Requests\FormRequest;
use App\Models\Contract;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Contract::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'kd'          => 'required|max:191|string|unique:' . (new Contract())->getTable() . ',kd',
            'name'        => 'required|max:191|string',
            'description' => 'nullable|max:500|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'kd'          => 'kode kontrak',
            'name'        => 'nama kontrak',
            'description' => 'deskripsi',
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->only([
            'kd', 'name', 'description'
        ]);
    }
}

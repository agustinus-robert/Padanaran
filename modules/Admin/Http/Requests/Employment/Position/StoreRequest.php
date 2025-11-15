<?php

namespace Modules\Admin\Http\Requests\Employment\Position;

use App\Http\Requests\FormRequest;
use App\Models\Position;
use Modules\Account\Models\EmployeePosition;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', EmployeePosition::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'position_id'       => 'required|string|max:191|exists:' . (new Position())->getTable() . ',id',
            'start_at'          => 'sometimes|date',
            'end_at'            => 'nullable|date|after:start_at'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'position_id'       => 'nama jabatan',
            'start_at'          => 'tanggal berlaku',
            'end_at'            => 'tanggal berakhir'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->validated();
    }
}

<?php

namespace Modules\Admin\Http\Requests\Inventory\Vehcile\VehcileSell;

use Auth;
use App\Http\Requests\FormRequest;
use Modules\Core\Enums\PlaceableTypeEnum;
use Modules\Core\Models\Tool;

class StoreRequest extends FormRequest
{
    public $placeable;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Supplier::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        
        return [
            'form.date_sell' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'form.date_sell' => 'required'
        ];
    }

    public function messages(): array {
         return [
            'form.date_sell' => 'Tanggal harus diisi'
        ];
    }
}

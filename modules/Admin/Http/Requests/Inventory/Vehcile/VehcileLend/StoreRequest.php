<?php

namespace Modules\Admin\Http\Requests\Inventory\VehcileLend;

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
            'form.number_sk' => 'required',
            'form.date_sk' => 'required',
            'form.start_date' => 'required',
            'form.end_date' => 'required',
            'form.vehcile_id' => 'required',
            'form.information' => 'required'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
          'form.number_sk' => 'Nomor SK',
          'form.date_sk' => 'Tanggal SK',
          'form.start_date' => 'Tanggal Mutasi',
          'form.end_date' => 'Periode bulan',
          'form.vehcile_id' => 'Kendaraan',
          'form.information' => 'Informasi'
        ];
    }

    public function messages(): array {
         return [
          'form.number_sk.required' => 'Nomor SK harus diisi',
          'form.date_sk.required' => 'Tanggal SK harus diisi',
          'form.start_date.required' => 'Tanggal Mutasi harus diisi',
          'form.end_date.required' => 'Periode bulan harus diisi',
          'form.vehcile_id.required' => 'Kendaraan harus diisi',
          'form.information.required' => 'Informasi harus diisi'
        ];
    }
}

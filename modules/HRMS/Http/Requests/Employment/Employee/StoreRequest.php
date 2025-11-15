<?php

namespace Modules\HRMS\Http\Requests\Employment\Employee;

use Illuminate\Validation\Rules\Enum;
use App\Rules\UniqueMetaValue;
use App\Http\Requests\FormRequest;
use Modules\Account\Models\User;
use Modules\Reference\Models\Country;
use Modules\Core\Models\CompanyContract;
use Modules\Core\Enums\WorkLocationEnum;
use Modules\HRMS\Models\Employee;
use Modules\HRMS\Models\EmployeeContract;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->can('store', Employee::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name'              => 'required|max:191|string',
            'username'          => 'required|min:4|max:191|regex:/^[a-z\d.]{4,20}$/|unique:' . (new User())->getTable() . ',username',
            'phone_code'        => ['required', 'in:' . Country::select('phones', 'kd')->get()->pluck('phones', 'kd')->flatten()->join(',')],
            'phone_number'      => ['required', 'numeric', new UniqueMetaValue(User::class, 'phone_number', $this->user()->getMeta('phone_number'))],
            'joined_at'         => 'required|date',
            'contract_id'       => 'required_unless:contract,1|exists:' . (new CompanyContract())->getTable() . ',id',
            'kd'                => 'required_unless:contract,1|string|max:191|unique:' . (new EmployeeContract())->getTable() . ',kd',
            'start_at'          => 'required_unless:contract,1|date|before:end_at',
            'end_at'            => 'required_unless:contract,1|date|after:start_at',
            'contract_file'     => 'required_unless:contract,1|file|mimes:pdf|max:5120',
            'work_location'     => ['required_unless:contract,1', new Enum(WorkLocationEnum::class)]
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function messages()
    {
        return [
            'username.unique' => 'Isian :attribute sudah digunakan oleh orang lain, silahkan gunakan :attribute lainnya.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'name'              => 'nama lengkap',
            'username'          => 'username',
            'phone_code'        => 'kode negara',
            'phone_number'      => 'nomor ponsel',
            'joined_at'         => 'tanggal bergabung',
            'contract_id'       => 'jenis perjanjian kerja',
            'kd'                => 'nomor perjanjian kerja',
            'start_at'          => 'tanggal berlaku',
            'end_at'            => 'tanggal berakhir',
            'contract_file'     => 'dokumen perjanjian kerja',
            'work_location'     => 'lokasi kerja'
        ];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $this->merge([
            'password' => substr(str_shuffle('0123456789ABCDEF'), 0, 6),
            'phone_whatsapp' => 1,
            'entered_at' => $this->input('nip'),
            'nip' => $this->input('nip')
        ]);

        return $this->only(array_merge(array_keys($this->validated()), ['contract', 'password', 'entered_at', 'nip']));
    }
}

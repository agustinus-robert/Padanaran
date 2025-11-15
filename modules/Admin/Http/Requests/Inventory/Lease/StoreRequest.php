<?php

namespace Modules\Asset\Http\Requests\Inventory\Lease;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use Carbon\CarbonPeriod;
use Modules\Account\Models\User;
use Modules\Core\Enums\BorrowableTypeEnum;
use Modules\HRMS\Models\Employee;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'receiver_id'          => 'required|exists:' . (new User())->getTable() . ',id',
            'inv.*.modelable_type' => ['required', new Enum(BorrowableTypeEnum::class)],
            'inv.*.modelable_id'   => 'required',
            'received_at'          => 'required',
            'returned_at'          => 'nullable',
            'for'                  => 'nullable|string|max:255',
            'description'          => 'nullable|string'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'receiver_id'          => 'penerima',
            'inv.*.modelable_type' => 'kategori inventaris',
            'inv.*.modelable_id'   => 'jenis inventaris',
            'received_at'          => 'dipinjam pada',
            'returned_at'          => 'dikembalikan pada',
            'for'                  => 'keperluan',
            'description'          => 'catatan'
        ];
    }


    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $period = CarbonPeriod::create(date('Y-m-d', strtotime($this->input('received_at'))), date('Y-m-d', strtotime($this->input('returned_at'))));
        $list = $period->toArray();

        return [
            'title' => $this->input('for'),
            'receiver_id' => $this->input('receiver_id'),
            'meta' => [
                'returned'  => $this->input('returned', 0),
                'for'       => $this->input('for'),
                'clause'    => $this->input('clause'),
                'description' => $this->input('description')
            ],
            'items' => $this->collect('inv')->map(
                function ($item) use ($period) {
                    return array_map(fn ($key, $value) => array_merge([
                        'borrowable'     => $borrowable = BorrowableTypeEnum::forceTryFrom($item['modelable_type']),
                        'modelable_type' => $borrowable->instance(),
                        'modelable_id'   => (float) $item['modelable_id'],
                        'giver_id'       => (float) Employee::find($borrowable->approver())->first()->user_id,
                        'received_at'    => min($period->toArray())->isSameDay($value) ? date('Y-m-d H:i:s', strtotime($this->input('received_at'))) : date('Y-m-d', strtotime($value)) . ' 08:00:00',
                        'returned_at'    => max($period->toArray())->isSameDay($value) ? date('Y-m-d H:i:s', strtotime($this->input('returned_at'))) : null,
                    ]), array_keys($period->toArray()), $period->toArray());
                }
            )->toArray(),
        ];
    }
}

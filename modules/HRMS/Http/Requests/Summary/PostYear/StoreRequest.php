<?php

namespace Modules\HRMS\Http\Requests\Summary\PostYear;

use App\Http\Requests\FormRequest;
use Modules\Core\Models\CompanySalarySlipComponent;
use Modules\Core\Models\CompanySalaryTemplate;
use Modules\HRMS\Models\EmployeeSalaryTemplateItem;

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
            'start_at'       => 'required|date',
            'end_at'       => 'required|date'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'start_at'                      => 'tanggal cut off',
            'end_at'                        => 'tanggal cut off',
            'result'                        => 'rekapitulasi',
        ];
    }

    /**
     * Map to float values.
     */
    public function mapFloat(array $items)
    {
        foreach ($items as $key => $item) {
            $result[$key] = is_array($item) ? $this->mapFloat($item) : (!in_array($key, ['m']) ? (float) $item : $item);
        }
        return $result ?? [];
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        $result = (array) $this->mapFloat($this->input('result'));

        $component_ids = collect($this->input('result.el'))->pluck('components.*')->flatten(1)->mapToGroups(fn($c) => [$c['id'] => $c['amount']])->toArray();
        $itemGroups    = EmployeeSalaryTemplateItem::whereIn('id', array_keys($component_ids))->get()->mapToGroups(fn($g) => [$g->component_id => $g->id]);
        $itemGroupsTk = $itemGroups->map(function ($values, $key) {
            return collect($values)->mapWithKeys(fn($value) => [$value => $key]);
        });

        $defaults      = collect(CompanySalaryTemplate::whereJsonContains('meta->default', true)->first()->components)->flatten(1)->whereIn('component_id', $itemGroupsTk->flatten());
        $cmpsal        = collect(CompanySalaryTemplate::whereJsonContains('meta->postyear', true)->first()->components)->flatten(1);

        foreach ($itemGroups as $key => $values) {
            $temp = [];
            $defaultComponentName = $defaults->firstWhere('component_id', $key)->name;
            foreach ($values as $k => $v) {
                $value[$key][$k] = $component_ids[$v];
                $temp = array_merge($value[$key][$k], $temp);
            }
            $cmp = collect($cmpsal)->firstwhere('name', $defaultComponentName);
            $thrComponentName = CompanySalarySlipComponent::find($cmp->component_id)->meta?->kd ?? null;
            $result['components'][$thrComponentName] = [
                'id' => $cmp->component_id ?? null,
                'total' => round(array_sum($temp) / $this->input('result.division')),
            ];
        }

        return [
            ...$this->only('start_at', 'end_at'),
            'result' => $result
        ];
    }
}

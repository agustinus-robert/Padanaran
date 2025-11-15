<?php

namespace Modules\Admin\Http\Controllers\API;

use Illuminate\Http\Request;
use Modules\Admin\Models\InfrastructureClassification;
use Modules\Reference\Http\Controllers\Controller;

class ClassificationController extends Controller
{
    /**
     * Search a listing of the resources..
     */
    public function search(Request $request)
    {
        $classifications = InfrastructureClassification::with('groups.types')->get();

        $result = [];
        foreach ($classifications as $classification) {
            foreach ($classification->groups ?? [['code' => '00', 'name' => null]] as $group) {
                foreach ($group->types ?? [['code' => '00', 'name' => null]] as $type) {
                    $result[] = [
                        'code' => implode('/', [$classification['code'], $group['code'], $type['code']]),
                        'name' => implode(' - ', array_filter([$classification['name'], $group['name'], $type['name']]))
                    ];
                }
            }
        }

        $data = collect($result)->filter(fn ($item) => preg_match('/' . $request->get('q', '') . '/i', $item['name']));

        return response()->success([
            'message' => 'Berikut adalah data hasil pencarian dengan query "' . $request->get('q', '') . '"',
            'data' => $data->map(fn ($v) => [
                'id' => $v['code'],
                'text' => $v['name']
            ])
        ]);
    }
}

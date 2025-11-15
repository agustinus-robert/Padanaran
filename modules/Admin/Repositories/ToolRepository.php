<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Tool;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait ToolRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'name_asset',
        'code',
        'code_unit',
        'code_sub',
        'code_goods',
        'price',
        'material',
        'year_buy',
        'qty',
        'total',
        'origin',
        'information',
        'address_primary',
        'conditional'
    ];

    /**
     * Store newly created resource.
     */
    public function storeTool(array $data)
    {

        try {
            DB::beginTransaction();

            $data['price'] = str_replace('.','',$data['price']);
            $data['total'] = str_replace('.','',$data['total']);
            $tool = new Tool(Arr::only($data, $this->keys));
            
            if ($tool->save()){
                DB::commit();
                return true;
            }

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateTool(array $data, $tool_id)
    {
        try {
            DB::beginTransaction();

            $tool = Tool::find($tool_id);

            $data['price'] = str_replace('.','',$data['price']);
            $data['total'] = str_replace('.','',$data['total']);
            
            if ($tool->update(Arr::only($data, $this->keys))) {
               DB::commit();
               return true;
            }

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroyTool($id)
    {
        if (Tool::where('id', $id)->delete()) {

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreTool($id)
    {
        if (Tool::onlyTrashed()->find($id)->restore()) {
            
            return true;
        }
        return false;
    }
}

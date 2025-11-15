<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\MutationTool;
use Modules\Admin\Models\MutationToolItem;
use Modules\Admin\Models\Tool;
use Modules\Admin\Models\Vehcile;
use Modules\Admin\Models\ToolLendItem;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait ToolLendItemRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'date_sk',
        'file_sk',
        'number_sk',
        'date_mutation',
    ];

    private $items = [
        'unit_from',
        'unit_to',
        'qty_mutation',
        'qty_src',
        'tool_id'
    ];

    private $edit_keys = [
        'date_sk',
        'file_sk',
        'date_mutation',
    ];

    /**
     * Store newly created resource.
     */
    public function storeLendPushtool(array $data)
    {
        try {
            DB::beginTransaction();

            $toolData = ToolLendItem::find($data['id']);
            $toolData->id = $data['id'];
            $toolData->forheit_slice = $data['forheit_slice'];
            $toolData->save();

            DB::commit();
            return true;

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
    public function restoreLendPushtool($id)
    {
        try {
            DB::beginTransaction();

            $toolData = ToolLendItem::find($id);
            $toolData->is_back = 1;
            $toolData->save();

            $tools = Tool::find($toolData->tool_id);
            $tools->id = $toolData->tool_id;
            $tools->qty = ($tools->qty + $toolData->qty);
            $tools->save();


            DB::commit();
            return true;

        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        }
    }

    public function returMutationtool($id){
         try {
            DB::beginTransaction();

            $arr_qty = [];
            $arr_finalQTY = [];

            $toolReturn = MutationToolItem::where('mutation_id', $id)->get()->toArray();
            

            foreach($toolReturn as $index => $value){
                $arr_qty[$value['tool_id']][] = $value['qty_mutation'];
            }

            
            foreach($arr_qty as $index => $value){
                foreach($value as $indexqty => $valueqty){
                    @$arr_finalQTY[$index] += $value[$indexqty]; 
                }
            }

             foreach($arr_finalQTY as $index => $value){
                $toolqty = Tool::find($index);
      
                $toolqty->id = $index; //already exists in database.
                $toolqty->qty = ($toolqty->qty + $value);
                $toolqty->save();
            }

            $mutateCurrentTool = MutationTool::find($id);
            $mutateCurrentTool->is_return = 1;
            $mutateCurrentTool->save();

           
           
          
            
                 
            DB::commit();
            return true;

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
    public function destroySellVehcile($id)
    {
        if (VehcileSell::where('id', $id)->delete()) {
            VehcileSellItem::where('vehcile_sell_id', $id)->delete();

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSellVehcile($id)
    {
        if (VehcileSell::onlyTrashed()->find($id)->restore()) {
            VehcileSellItem::onlyTrashed()->where('vehcile_sell_id', $id)->restore();

            return true;
        }
        return false;
    }
}

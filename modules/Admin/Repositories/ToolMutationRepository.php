<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\MutationTool;
use Modules\Admin\Models\MutationToolItem;
use Modules\Admin\Models\Tool;
use Modules\Admin\Models\Vehcile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait ToolMutationRepository
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
    public function storeMutationtool(array $data)
    {
        try {
            DB::beginTransaction();

            $location_mutation_tool = 'file_mutation_tool/'.uniqid();
            $data['file_sk']->storeAs($location_mutation_tool, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_mutation_tool.'/'.$data['file_sk']->getFilename();
            $tool_mutation = new MutationTool(Arr::only($data, $this->keys));
            
            $tool_mutation->save();

            $arr_qty = [];
            $arr_finalQTY = [];
            

            foreach($data['generate'] as $index => $value){
                $items = new MutationToolItem();
                $arr_qty[$data['id_tool'][$index]][] = $data['qty'][$index];
                $items->unit_from = $data['unit_from'][$index];
                $items->unit_to = $data['unit_to'][$index];
                $items->qty_mutation = $data['qty'][$index];
                $items->qty_src = $data['qty_from'][$index];
                $items->tool_id = $data['id_tool'][$index];
                $items->mutation_id = $tool_mutation->id;
                $items->save();
            }

            foreach($arr_qty as $index => $value){
                foreach($value as $indexqty => $valueqty){
                    @$arr_finalQTY[$index] += $value[$indexqty]; 
                }
            }


            foreach($arr_finalQTY as $index => $value){
                $toolqty = Tool::find($index);
      
                $toolqty->id = $index; //already exists in database.
                $toolqty->qty = ($toolqty->qty - $value);
                $toolqty->save();
            }

            

            

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
    public function updateMutationTool(array $data, $mutationToolId)
    {
        try {
            DB::beginTransaction();


            $toolMutation = MutationTool::find($mutationToolId);

            if(is_array($data['file_sk'])){
                $location_mutation_tool = 'file_mutation_tool/'.uniqid();
                $data['file_sk']->storeAs($location_mutation_tool, $data['file_sk']->getFilename(), 'public');
                $data['file_sk'] = $location_mutation_tool.'/'.$data['file_sk']->getFilename();
            }

            $toolMutation->update(Arr::only($data, $this->edit_keys));

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

<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Tool;
use Modules\Admin\Models\ToolSell;
use Modules\Admin\Models\ToolSellItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait ToolSellRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'invoice',
        'date_sell',
        'file_sk',
    ];

    private $item_keys = [
        'tool_sell_id',
        'tool_id',
        'tool_price',
        'tool_date'
    ];

    /**
     * Store newly created resource.
     */
    public function storeSellTool(array $data)
    {
        try {
            DB::beginTransaction();

            $location_tool = 'file_tool_sell/'.uniqid();

            $data['file_sk']->storeAs($location_tool, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_tool.'/'.$data['file_sk']->getFilename();
            

            $data['invoice'] = 'INV/PENJUALAN/TOOL/'.random_int(100000, 999999).'/'.date('Y');
            $tool = new ToolSell(Arr::only($data, $this->keys));
            $tool->save();

            $arr_qty = [];
            $arr_finalQTY = [];
            

            foreach($data['generate'] as $index => $value){
                $items = new ToolSellItem();
                $arr_qty[$data['id_tool'][$index]][] = $data['qty'][$index];
                $items->tool_sell_id = $tool->id;
                $items->tool_id = $data['id_tool'][$index];
                $items->tool_price = $data['harga'][$index];
                $items->qty = $data['qty'][$index];
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
    public function updateSellTool(array $data, $items, $tool_id)
    {
        try {
            DB::beginTransaction();


            $tool = ToolSell::find($tool_id);

            if(is_array($data['file_sk'])){
                $location_tool = 'file_tool_sell/'.uniqid();
                $data['file_sk']->storeAs($location_tool, $data['file_sk']->getFilename(), 'public');
                $data['file_sk'] = $location_tool.'/'.$data['file_sk']->getFilename();
            }

            if ($tool->update(Arr::only($data, $this->keys))) {
                if(count($items) > 0){
                    ToolSellItem::where('tool_sell_id', $tool_id)->forceDelete();
                    
                    foreach($items as $index => $value){
                        $save_item = [
                            'tool_sell_id' => $tool->id,
                            'tool_id' => $value,
                            'tool_price' => $data['harga'][$index]
                        ];  

                        $tool_sell = new ToolSellItem(Arr::only($save_item, $this->item_keys));
                        $tool_sell->save();
                    }
                }
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
     * Remove the current resource.
     */
    public function destroySelltool($id)
    {
        if (ToolSell::where('id', $id)->delete()) {
            ToolSellItem::where('tool_sell_id', $id)->delete();

            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSelltool($id)
    {
        if (ToolSell::onlyTrashed()->find($id)->restore()) {
            ToolSellItem::onlyTrashed()->where('tool_sell_id', $id)->restore();

            return true;
        }
        return false;
    }
}

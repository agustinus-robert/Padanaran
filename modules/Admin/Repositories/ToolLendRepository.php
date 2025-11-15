<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\Tool;
use Modules\Admin\Models\ToolLend;
use Modules\Admin\Models\ToolLendItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait ToolLendRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'invoice',
        'date_sk',
        'file_sk',
    ];

    private $item_keys = [
        'tool_id',
        'forheit_price',
        'start_date',
        'end_date'
    ];

    /**
     * Store newly created resource.
     */
    public function storeLendTool(array $data)
    {
        try {
            DB::beginTransaction();

            $location_tool = 'file_tool_lend/'.uniqid();

            $data['file_sk']->storeAs($location_tool, $data['file_sk']->getFilename(), 'public');
            $data['file_sk'] = $location_tool.'/'.$data['file_sk']->getFilename();
            

            $data['invoice'] = 'INV/PEMINJAMAN/TOOL/'.random_int(100000, 999999).'/'.date('Y');
            $tool = new ToolLend(Arr::only($data, $this->keys));
            
            if ($tool->save()){
                if(count($data) > 0){    
                    $save_item = [];
                    $arr_qty = [];


               

                    foreach($data['generate'] as $index => $value){
                        $tool_lend = new ToolLendItem();
                        $arr_qty[$data['id_tool'][$index]][] = $data['qty'][$index];
                        

                        $tool_lend->tool_lend_id = $tool->id;
                        $tool_lend->tool_id = $data['id_tool'][$index];
                        $tool_lend->qty = $data['qty'][$index]; 
                        $tool_lend->forheit_price = $data['forheit'][$index];
                        $tool_lend->start_date = $data['start_date'][$index];
                        $tool_lend->end_date = $data['end_date'][$index];
                        
                        $tool_lend->save();

                        // $tool_sell = new ToolLendItem(Arr::only($save_item, $this->item_keys));
                        // $tool_sell->save();
                    }
                    
                   

                    foreach($arr_qty as $index => $value){
                        foreach($value as $indexqty => $valueqty){
                            @$arr_finalQTY[$index] += $value[$indexqty]; 
                        }
                    }

                   
                    foreach($arr_finalQTY as $index => $value){
                        $toolqty = Tool::find($index);
                        $hasil = ($toolqty->qty - $value);
                        $toolqty->id = $index; //already exists in database.
                        $toolqty->qty = $hasil;
                        $toolqty->save();
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

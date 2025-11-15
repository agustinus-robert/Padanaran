<?php

namespace Modules\Admin\Repositories;

use Modules\Admin\Models\BuildingsLandsFloor;
use Modules\Admin\Models\BuildingsLandsRoom;
use Modules\Admin\Models\TransactionFloorRoom;
use Modules\Admin\Models\Room;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use DB;

trait TransactionSellFloorRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'transaction_sell_id','building_id','floor_id','room_id'
    ];

    /**
     * Store newly created resource.
     */
    public function storeFloorRoom($data, $trans_id, $id, $form)
    {
         try {
            DB::beginTransaction();
            foreach($data as $index => $value){
                $transRoom = new TransactionFloorRoom;
                $transRoom->transaction_sell_id = $trans_id;
                $transRoom->building_id = $value->building_id;
                $transRoom->floor_id = $value->floor_id;
                $transRoom->room_id = $value->id;
                $transRoom->save();

                $room = Room::find($value->id);
                $room->id = $value->id;
                $room->is_lend = 1;
                $room->save();
            }

            $transactionOnfloor = new BuildingsLandsFloor;
            $transactionOnfloor->floor_id = $id;
            $transactionOnfloor->building_sell_id = $trans_id;
            $transactionOnfloor->invoice = 'INV/LEND/ONFLOOR/'.random_int(100000, 999999).'/'.date('Y');
            $transactionOnfloor->date_start = $form['date_start'];
            $transactionOnfloor->month_period = $form['month_period'];
            $transactionOnfloor->forheit = $form['forheit'];
            $transactionOnfloor->save();

            DB::commit();
            return true;
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        } 
    }

    public function storeForheit($id, $sell_id, $form){
        $buildId = BuildingsLandsFloor::where(['floor_id' => $sell_id, 'forheit_status' => null, 'building_sell_id' => $id])->first();

        try {
            DB::beginTransaction();

            $transactionOnfloor = BuildingsLandsFloor::find($buildId->id);
            $transactionOnfloor->id = $buildId->id;
            $transactionOnfloor->forheit_slice = $form['forheit_slice'];
            $transactionOnfloor->save();

            DB::commit();
            return true;
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        } 
    }

    public function storeRoomForheit($id, $sell_id, $form){
        $room = Room::find($id);
        $buildId = BuildingsLandsRoom::where(['room_id' => $id, 'floor_id' => $room->floor_id, 'forheit_status' => null, 'building_sell_id' => $sell_id])->first();

        try {
            DB::beginTransaction();

            $transactionOnfloor = BuildingsLandsRoom::find($buildId->id);
            $transactionOnfloor->id = $buildId->id;
            $transactionOnfloor->forheit_slice = $form['forheit_slice'];
            $transactionOnfloor->save();

            DB::commit();
            return true;
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        } 
    }

    public function storeRoom($id, $sell_id, $status, $form){
        try {
            DB::beginTransaction();

            $room = Room::find($id);
            $room->id = $id;

            if($status == 'back_sell'){
                $room->is_sell = 1;
                $room->save();

                $transRoom = new TransactionFloorRoom;
                $transRoom->transaction_sell_id = $sell_id;
                $transRoom->building_id = $room->building_id;
                $transRoom->floor_id = $room->floor_id;
                $transRoom->room_id = $room->id;
                $transRoom->save();
            } else {
                $room->is_lend = 1;
                $room->save();

                $transactionOnRoom = new BuildingsLandsRoom;
                $transactionOnRoom->floor_id = $room->floor_id;
                $transactionOnRoom->room_id = $room->id;
                $transactionOnRoom->building_sell_id = $sell_id;
                $transactionOnRoom->invoice = 'INV/LEND/ONROOM/'.random_int(100000, 999999).'/'.date('Y');
                $transactionOnRoom->date_start = $form['date_start'];
                $transactionOnRoom->month_period = $form['month_period'];
                $transactionOnRoom->forheit = $form['forheit'];
                $transactionOnRoom->save();            
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

    public function restoreFloorRoom($id, $sell_id){
        $buildId = BuildingsLandsFloor::where(['floor_id' => $id, 'forheit_status' => null,  'building_sell_id' => $sell_id])->first();

        try {
            DB::beginTransaction();

            $transactionOnFloor = BuildingsLandsFloor::find($buildId->id);
            $transactionOnFloor->id = $buildId->id;
            $transactionOnFloor->forheit_status = 1;
            $transactionOnFloor->save();

            $transactionOnRoom = Room::where('floor_id', $buildId->floor_id);
            $transactionOnRoom->update(['is_lend' => null]);
            
            
            DB::commit();
            return true;
        } catch (\PDOException $e) {
            // Woopsy
            dd($e);
            DB::rollBack();
            return false;
        } 
    }

    public function restoreRoomData($id, $sell_id){
        $buildId = BuildingsLandsRoom::where(['room_id' => $id, 'forheit_status' => null,  'building_sell_id' => $sell_id])->first();

        try {
            DB::beginTransaction();

            $transactionOnTheRoom = BuildingsLandsRoom::find($buildId->id);
            $transactionOnTheRoom->id = $buildId->id;
            $transactionOnTheRoom->forheit_status = 1;
            $transactionOnTheRoom->save();

            $transactionOnRoom = Room::where('id', $buildId->room_id);
            $transactionOnRoom->update(['is_lend' => null]);
            
            
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
    public function updateRoom(array $data, $id)
    {
        $room = Room::find($id);
        if ($room->update(Arr::only($data, $this->keys))) {
            return true;
        }
        return false;
    }

    /**
     * Remove the current resource.
     */
    public function destroyRoom($id)
    {
        if (Room::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    // public function restoreRoom($id)
    // {
    //     if (Room::onlyTrashed()->find($id)->restore()) {
    //         return true;
    //     }
    //     return false;
    // }
}

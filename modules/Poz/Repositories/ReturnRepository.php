<?php

namespace Modules\Poz\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Models\ReturnGoods;
use Modules\Poz\Models\ReturnGoodsItems;
use DB;

trait ReturnRepository
{
    /**
     * Define the form keys for resource
     */
    private $keys = [
        'reference',
        'customer_name',
        'return_status',
        'sub_total',
        'grand_total',
        'return_note',
        'casier_note',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * Store newly created resource.
     */
    public function addProductStock($selected, $outletForm)
    {
        foreach ($selected as $item) {
            $returItem = ReturnGoodsItems::where([
                'id' => $item['id']
            ])->first();

            if ($returItem) {
                $returItem->qty += $item['qty'];
                $returItem->save();
            } else {
                ReturnGoodsItems::create([
                    'product_id' => $item['id'],
                    'outlet_id'  => $outletForm,
                    'qty'        => $item['qty']
                ]);
            }
        }

        return true;
    }


    public function storeReturn(array $invoice, array $returnItem, $outletForm = '')
    {
        DB::beginTransaction();

        try {
            $data['reference'] = 'REF' . '-' . rand();
            $data['customer_name'] = $invoice['customer_name'];
            $data['return_status'] = $invoice['return_status'];
            $data['sub_total'] = $invoice['sub_total'];
            $data['grand_total'] = $invoice['grand_total'];
            $data['return_note'] = $invoice['return_note'];
            $data['casier_note'] = $invoice['casier_note'];
            $data['created_by'] = \Auth::user()->id;

            $retur = new ReturnGoods(Arr::only($data, $this->keys));
            if ($retur->save()) {

                if ($outletForm) {
                    $retur->outlets()->attach($outletForm);
                }

                foreach ($returnItem as $key => $value) {
                    $returnItems = new ReturnGoodsItems();
                    $returnItems->return_id = $retur->id;
                    $returnItems->product_id = $value['id'];
                    $returnItems->outlet_id = ($outletForm == '' ? 0 : $outletForm);
                    $returnItems->qty = $value['qty'];
                    $returnItems->created_by = \Auth::user()->id;
                    $returnItems->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    /**
     * Update the current resource.
     */
    public function updateReturn(array $inv, $returnItem, $id, $outletForm)
    {
        DB::beginTransaction();

        try {
            $data['reference'] = 'REF' . '-' . rand();
            $data['customer_name'] = $invoice['customer_name'];
            $data['return_status'] = $inv['retun_status'];
            $data['sub_total'] = $inv['sub_total'];
            $data['grand_total'] = $inv['grand_total'];
            $data['return_note'] = $inv['return_note'];
            $data['casier_note'] = $inv['casier_note'];
            $data['created_by'] = \Auth::data()->id;
            $retur = ReturnGoods::find($id);

            ReturnGoodsItems::where('return_id', $id)->delete();

            if ($retur->update(Arr::only($data, $this->keys))) {
                if ($outletForm) {
                    $retur->outlets()->attach($outletForm);
                }

                foreach ($returnItem as $key => $value) {
                    $returItems = new ReturnGoodsItems();
                    $returItems->return_id = $retur->id;
                    $returItems->product_id = $value['id'];
                    $returItems->outlet_id = ($outletForm == '' ? 0 : $outletForm);
                    $returItems->qty = $value['qty'];
                    $returItems->created_by = \Auth::user()->id;
                    $returItems->save();
                }
            }

            if ($inv['return_status'] == 3) {
                if (!$this->reduceProductStock($returnItem, $outletForm)) {
                    DB::rollback();
                    return 'stock';
                }
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return false;
        }
    }

    /**
     * Remove the current resource.
     */
    public function destroySale($id)
    {
        if (Sale::where('id', $id)->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Restore the current resource.
     */
    public function restoreSale($id)
    {
        if (Sale::onlyTrashed()->find($id)->restore()) {
            return true;
        }
        return false;
    }
}

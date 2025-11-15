<?php

namespace Modules\Admin\Http\Livewire\Builder;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Events\MenuOrderCreated;
use Redirect;
use DB;

class Order extends Component
{

    public $order_menu = [];
    public $data_id;

    public function mount(){
         $data_to_order = DB::table('menu_order')->where('id', 1)->first();
         if(isset($data_to_order->id)){
            $this->data_id = $data_to_order->id;
            $this->order_menu = json_decode($data_to_order->menu_text, true);
        }

    }

    public function submitForm(){
        $arr_save['menu_text'] = $this->order_menu;

        if($this->data_id){
            $arr_save['updated_by'] = \Auth::user()->id;
            DB::table('menu_order')->where('id', $this->data_id)->update($arr_save);
            // event(new MenuOrderCreated('Pengurutan menu telah diubah, silahkan <b>Sync</b>'));
        } else {
            $arr_save['created_by'] = \Auth::user()->id;
            $arr_save['updated_by'] = \Auth::user()->id;

            DB::table('menu_order')->insert($arr_save);
            // event(new MenuOrderCreated('Pengurutan menu telah ditambahkan, silahkan <b>Sync</b>'));
        }

        $url = route('admin::builder.order.index');
        return redirect($url)->with('msg', "Data berhasil disimpan");
    }

    private function countas_menus($arr, $compare){
        if(!@$arr[$compare]){
            return ['id' => $compare];
        }

        // if(count($dif) == 0){
        //     foreach($result as $index => $value){
        //         $dif[] = ['id' => $value->id];
        //     }

        //     return $dif;
        // }
    }

    public function render()
    {
        $dif = [];
        $arr = [];
        $arr2 = [];

        $get_end_array = [];
        $the_order = $this->order_menu;

        $sql = "SELECT
        `menu`.`id`,
        `menu`.`title` from `menu`";
        $sql .= " UNION ";
        $sql .= "SELECT
        `categoryzation_menu`.`id`,
        `categoryzation_menu`.`title` from `categoryzation_menu`";

        $result = DB::select($sql);

        $res_there = [];
        foreach($result as $index1 => $value1){
            $res_there[$value1->id] = $value1->id;
        }

        if(gettype($the_order) == 'array'){
            foreach($the_order as $index => $value){
                if(isset($res_there[$value['id']]) && $res_there[$value['id']] == $value['id']){
                    $arr[$value['id']] = [];
                    $arr[$value['id']]['id'] = $value['id'];

                    if(isset($value['children'])){
                        foreach($value['children'] as $index2 => $value2){
                            if(isset($res_there[$value2['id']]) && $res_there[$value2['id']] == $value2['id']){

                                if(isset($value2['children'])){

                                    //foreach($arr[$value['id']]['children'] as $index3 => $value3){
                                        $arr[$value['id']]['children'][$value2['id']] = $value2['children'];
                                    //}
                                } else {
                                    $arr[$value['id']]['children'][$value2['id']] = $value2['id'];
                                }
                            }
                        }
                    }
                }
            }
        }





        $dkk = [];

        foreach($res_there as $index1 => $value1){
            if(isset($arr[$index1]) && !$arr[$index1]){
                foreach($arr as $index2 => $value2){
                    if(isset($value2['children'])){
                        foreach($value2['children'] as $index3 => $value3){
                            unset($arr[$value3]);
                        }
                    }
                }

                $arr[$index1] = ['id' => $index1];
            }

            if(!isset($arr[$value1])){
                $dkk[$value1] = ['id' => $value1];
                if(get_menu_order_child($value1) == 'sama'){
                    unset($dkk[$value1]);
                }
            }

        }


        if(gettype($arr) == 'array'){
            $get_end_array = array_merge((array) $arr, (array) $dkk);
        }

        $data['order_menu'] = $get_end_array;


       return view('admin::livewire.builder.order', ['data' => $data]);
    }
}

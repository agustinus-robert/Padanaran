<?php

namespace Modules\Admin\Http\Livewire\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\Post;
use Modules\Admin\Models\PostF;
use Modules\Admin\Models\MenuRelated;
use Cookie;
use Redirect;
use DB;


class Posting extends Component
{
    use AuthorizesRequests;

    use WithFileUploads;

    public $data_id;
    public $title = '';
    public $content = [];
    public $countsdt;
    public $cookie;
    public $language = 'id';
    public $menu_id;
    public $image_desc;
    public $dropify;
    public $tags;
    public $stt;
    public $sh_photo;
    public $category = [];
    public $contentpost;
    public $manyArr = [];
    public $allForm = [];
    public $selectFormAdd = '';

    #[Validate('image|max:1024')] // 1MB Max
    public $photo;

    public function mount(Request $req){
        $id = strtok($req->post_id, '/');
        $this->menu_id = $req->id_menu;
        $this->stt = DB::table('menu')->where('id', $this->menu_id)->first()->type;
        $pst_code = DB::table('menu')->where('id', $this->menu_id)->first();

       // session()->forget(['eng']);
        //dd($req->session()->get("posting"));

        //dd($req->session()->get("posting"));
        $menuRelated = MenuRelated::where('from_menu', $req->id_menu)->get();

        foreach($menuRelated as $rel => $varel){
            $this->manyArr[$varel->with_menu] = $varel->with_menu;
        }

        $this->allForm = PostF::get();

        if(!empty($id) && is_string($id)){

            if(count((array) json_decode($pst_code->post_code)) > 0){
                 foreach($pst_code as $index => $value){
                    if($index == 'post_code'){
                        $i = 0;
                        foreach(json_decode($value, true) as $index_pc => $value_pc){
                            $this->countsdt[] = 'post'.$i;
                            $i++;
                        }
                    }
                }
            } else {
                $this->countsdt = [];
            }

            $this->data_id = $id;
            $data_to_post = DB::table('post')->where('id', $id)->first();
            $category_to_post = DB::table('post_has_category')->where('post_id', $id)->get()->toArray();

            // $this->title = $data_to_post->title;
            $this->sh_photo = $data_to_post->location.'/'.$data_to_post->image;

            $this->tags = $data_to_post->tags;
            $this->image_desc = @$data_to_post->alt_image;
            $arr = [];
            $helper_arr = [];

            $titles = 'title';
            $imagez = 'media_description';
            $metasd = 'meta_description';
            array_push($this->countsdt, $titles);
            array_push($this->countsdt, $imagez);
            array_push($this->countsdt, $metasd);

            $general = [];
            foreach($this->countsdt as $key => $val){
                $general[$val] = '';
            }


            foreach($general as $indextion => $valuetion){
                foreach(json_decode($data_to_post->content, true) as $index => $value){
                    foreach($value as $key2 => $value2){
                        if($indextion == $key2){
                            $arr[$index][$key2] = $value2;
                        }
                    }
                }
            }


            if(empty($req->session()->get("posting"))) {
                $titles = 'title';
                $imagez = 'media_description';
                $metasd = 'meta_description';

                array_push($this->countsdt, $titles);
                array_push($this->countsdt, $imagez);
                array_push($this->countsdt, $metasd);

                $general = [];
                foreach($this->countsdt as $key => $val){
                    $general[$val] = '';
                }

                $hs = [];
                $unused_hs = [];
                foreach($general as $key2 => $val2){
                    foreach($arr as $idex => $videx){
                        if(isset($videx[$key2])){
                            $hs[$idex] = $videx;
                        } else {
                            $unused_hs[$idex][$key2] = '';
                        }
                    }
                }


                if(count($unused_hs) > 0){
                    $arr3 = array();


                    foreach($hs as $key=>$val)
                    {
                       $arr3[$key] = $val;
                       if(isset($unused_hs[$key])){
                            $arr3[$key] = array_merge($val, $unused_hs[$key]);
                        }
                    }

                    $req->session()->put("posting", $arr3);
                } else {
                    $req->session()->put("posting", $hs);
                }
            }

            if(is_countable($req->session()->get("posting")) && count($req->session()->get("posting")) > 0){


                foreach($req->session()->get("posting")[$this->language] as $key => $val){
                    $this->content[$key] = $val;
                    $helper_arr[str_replace('post', '', $key)] = $val;
                }


                $this->dispatch('helper', $helper_arr);
            }

            foreach($category_to_post as $index => $val){
                $this->category[$val->parameter] = $val->tags_id;
            }



            $this->menu_id = $data_to_post->menu_id;

        } else {
            if(count((array) json_decode($pst_code->post_code)) > 0){
                foreach($pst_code as $index => $value){
                    if($index == 'post_code'){
                        $i = 0;
                        foreach(json_decode($value, true) as $index_pc => $value_pc){
                            $this->countsdt[] = 'post'.$i;
                            $i++;
                        }
                    }
                }
            } else {
                $this->countsdt = [];
            }

            //dd($this->countsdt);

            if(isset($req->session()->get("posting")[$this->language])) {
                if(is_countable($req->session()->get("posting")[$this->language]) && count($req->session()->get("posting")[$this->language]) > 0){
                    foreach($req->session()->get("posting")[$this->language] as $key => $value){
                            $arr[$key] = $value;
                            $this->content = $arr;
                    }

                }
            } else {
                $titles = 'title';
                $imagez = 'media_description';
                $metasd = 'meta_description';

                array_push($this->countsdt, $titles);
                array_push($this->countsdt, $imagez);
                array_push($this->countsdt, $metasd);

                $arr = [];
                foreach($this->countsdt as $key => $val){
                    $arr[$val] = '';
                }

                $req->session()->put("posting.".'id', $arr);
            }
           // dd($this->content);


            if(isset($_COOKIE['_x_content'])){

                $contents = json_decode($_COOKIE['_x_content'], true);
                if(is_countable($contents) && count($contents) > 0){
                   $this->cookie = $contents;
                }
            }

            if(isset($_COOKIE['_x_language'])){
                $this->language = trim($_COOKIE['_x_language'], '"');
            }
        }

    }

    public function clearSession(Request $request){
        $request->session()->forget('posting.id');
        //d($request->session()->get("posting"));
        $titles = 'title';
        $imagez = 'media_description';
        $metasd = 'meta_description';

        array_push($this->countsdt, $titles);
        array_push($this->countsdt, $imagez);
        array_push($this->countsdt, $metasd);

        $arr = [];
        foreach($this->countsdt as $key => $val){
            $arr[$val] = '';
        }

        $request->session()->put("posting.".'id', $arr);
    }

    public function selectForm($event){

    }

    public function moneys($money, $name, Request $request){
        $exp = explode('.', $name);
        $this->{$exp[0]}[$exp[1]] = number_format(round($money, 0), 0, ",", ".");
        $request->session()->put("posting.".$this->language.'.'.$exp[1], $this->{$exp[0]}[$exp[1]]);
    }

    public function selectlang($event, Request $request){
        $this->language = $event;

        if(!isset($request->session()->get("posting")[$this->language])){

            $titles = 'title';
            $imagez = 'media_description';
            $metasd = 'meta_description';

            array_push($this->countsdt, $titles);
            array_push($this->countsdt, $imagez);
            array_push($this->countsdt, $metasd);

            $arr = [];
            foreach($this->countsdt as $key => $val){
                $arr[$val] = '';
            }


            $request->session()->put("posting.".$this->language, $arr);
            $this->dispatch('helper', $arr);
        } else {
            $arr = [];
            foreach($request->session()->get("posting")[$this->language] as $key => $val){
                if(isset($this->content[$key])){
                    $this->content[$key] = $val;
                    $arr[str_replace('post', '', $key)] = $val;
                }
            }

            $this->dispatch('helper', $arr);
        }
    }

    public function helperlanguage($event, Request $request){
        if($event !== 'kosong'){
            if(is_countable($request->session()->get("posting")) && count($request->session()->get("posting"))){
                $request->session()->get("posting")[$this->language] = $this->content;

                if(isset($request->session()->get("posting")[$this->language])){
                    foreach($request->session()->get("posting")[$this->language] as $key => $val){
                        if(isset($this->content[$key])){
                            $request->session()->put("posting.".$this->language.'.'.$key, $this->content[$key]);
                        }
                    }
                }
            } else {

                $request->session()->put("posting.".$this->language, $this->content);
            }
        }


        //$this->dispatch('helper', $event);
    }

    public function checkSession(Request $request){
       dd($request->session()->get("posting"));
    }

    public function submitForm(Request $request){

        try{

        $location = 'image_posting/'.$this->menu_id.'/'.uniqid();

        if($this->data_id){
            $post = Post::find($this->data_id);
        } else {
            $post = new Post();
        }

        $arr = [];

        foreach($request->session()->get("posting") as $key => $value){
            foreach($value as $key2 => $value2){
                if($key2 == 'title'){
                    $arr[$key]['slug'] = strtolower(str_replace(' ','-', $value2));
                }
                $arr[$key][$key2] = $value2;
            }
        }



        $post->alt_image = $this->image_desc;


        $post->content = json_encode($arr);


        $post->menu_id = $this->menu_id;
        // $post->created_by = 1;
        // $post->updated_by = 1;


        if(!empty($this->dropify)){
            $post->image = $this->dropify->getFilename();
            $post->location = $location;
        }
        $post->tags = $this->tags;

        if(!empty($this->dropify)){
            $this->dropify->storeAs($location, $this->dropify->getFilename(), 'public');
        }
        //Storage::disk('local')->put(time() . '.' . 'example.txt', 'Contents');


         if($this->data_id){
            $post->updated_by = \Auth::user()->id;
           // DB::table('post')->where(['id' => $this->data_id])->update($arr_save);
            // foreach($request->session()->get("posting") as $key => $value){
            //     foreach($value as $key2 => $value2){
            //         if($key2 == 'title'){
            //             $post->setMeta($key, $value2);
            //         }
            //     }
            // }


            $post->save();

            DB::table('post_has_category')->where(['post_id' => $this->data_id])->delete();

            foreach($this->category as $index => $value){
                if(!empty($value)){
                    DB::table('post_has_category')->insert(['post_id' => $this->data_id, 'tags_id' => $value, 'parameter' => $index, 'created_by' => \Auth::user()->id, 'updated_by' => \Auth::user()->id]);
                }
            }


            $arr_log['menu_id'] = $this->menu_id;
            $arr_log['post_id'] = $this->data_id;
            $arr_log['action'] = 'update';
            $arr_log['created_by'] = \Auth::user()->id;
            $arr_log['updated_by'] = \Auth::user()->id;

            DB::table('user_log')->insert($arr_log);
        } else {
           $post->created_by = \Auth::user()->id;
           $post->updated_by = \Auth::user()->id;
           $post->status = $this->stt;

            foreach($request->session()->get("posting") as $key => $value){
                foreach($value as $key2 => $value2){
                    if($key2 == 'title'){
                       if($this->stt == 4){
                            $post->setMeta('content', $value2);
                        }
                    }
                }
            }

           //DB::table('post')->insert($arr_save);

           $post->save();

           foreach($this->category as $index => $value){
                if(!empty($value)){
                    DB::table('post_has_category')->insert(['post_id' => $post->id, 'tags_id' => $value, 'parameter' => $index, 'created_by' => \Auth::user()->id, 'updated_by' => \Auth::user()->id]);
                }
            }

            $arr_log['menu_id'] = $this->menu_id;
            $arr_log['post_id'] = $post->id;
            $arr_log['action'] = 'insert';
            $arr_log['status'] = 'publish';
            $arr_log['created_by'] = \Auth::user()->id;
            $arr_log['updated_by'] = \Auth::user()->id;

            DB::table('user_log')->insert($arr_log);

        }
            $url = \URL::route('admin::builder.posting.index') . "?id_menu=".$this->menu_id;
            return redirect($url)->with('msg', "Data berhasil disimpan");
        } catch(\Exception $e){
       // do task when error
          dd($e->getMessage());   // insert query
        }

    }

     protected function cleanupOldUploads()
    {

        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            // On busy websites, this cleanup code can run in multiple threads causing part of the output
            // of allFiles() to have already been deleted by another thread.
            if (! $storage->exists($filePathname)) continue;

            $yesterdaysStamp = now()->subSeconds(4)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }

    public function render()
    {
        $data = DB::table('menu')->where('id', $this->menu_id)->first();

        return view('admin::livewire.builder.posting', ['data' => $data]);

    }
}

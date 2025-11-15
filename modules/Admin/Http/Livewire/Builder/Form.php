<?php
namespace Modules\Admin\Http\Livewire\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\PostF;
use Modules\Admin\Models\PostFBuilder;
use Modules\Admin\Enums\FormBuilderEnum;
use Modules\Admin\Http\Requests\Builder\Form\StoreRequest;
use Illuminate\Support\Facades\View;

use Cookie;
use Redirect;
use DB;


class Form extends Component
{

	public $menu_id;
	public $title;
	public $arr = [];
	public $form = [];
	public $id = '';
	public $bag = [];

	public function mount(Request $req){
		$this->menu_id = $req->id_menu;
		$data['formEnum'] = FormBuilderEnum::cases();

		if(isset($req->post_id)){
			$this->id = strtok($req->post_id, '/');
			$postF = PostF::find($this->id);
			$this->title = $postF->title;
			$postBuilder = PostFBuilder::where('post_form_id', $this->id)->get();


			foreach($postBuilder as $key => $val){
				$data['rand'] = rand();
				$data['arr'][$data['rand']] = $val;

				$this->arr[$data['rand']]['label'] = $val['title'];
				$this->arr[$data['rand']]['type'] = $val['type'];

				$this->form[$data['rand']] = view('admin::layouts.components.form', $data)->render();
			}

			// foreach()
			//$this->form[rand()] =
		}
	}

	protected function rules()
    {
        return (new StoreRequest($this->form))->rules();

    }

    protected function messages(){
        return (new StoreRequest($this->form))->messages();
    }

	public function add(){
		$data['rand'] = rand();

    	$data['formEnum'] = FormBuilderEnum::cases();
		$this->form[$data['rand']] = view('admin::layouts.components.form', $data)->render();
	}

	public function erase($rand){
		unset($this->form[$rand]);

		if(isset($this->arr[$rand])){
			unset($this->arr[$rand]);
		}
	}

	public function type($rand, $value){
		$this->arr[$rand]['type'] = $value;
	}


	public function save(Request $request){
		$this->validate();

		if(count($this->form) > 0){
			DB::beginTransaction();

	        try {
	            $savePost = PostF::updateOrCreate(['id' => $this->id],[
	                'title' => $this->title,
	                'menu_id' => $this->menu_id
	            ]);

	            if(isset($this->id)){
	            	PostFBuilder::where('post_form_id', $this->id)->delete();
	            }

	            foreach($this->arr as $key => $val){
		            PostFBuilder::updateOrCreate([
		            	'post_form_id' => $savePost->id,
		            	'title' => $val['label'],
		            	'type' => $val['type']
		            ]);
	        	}

	            $this->alert('success', 'Form telah berhasil disimpan', [
	                'position' => 'center'
	            ]);

	            DB::commit();

	            $url = \URL::route('admin::builder.posting.index') . "?id_menu=".$this->menu_id;
	            return redirect($url)->with('msg', "Data berhasil disimpan");
	        } catch (\Exception $e) {
	            DB::rollback();
	            dd($e);
	        }
    	} else {
    		$this->alert('error', 'Silahkan tambahkan inputan form',  [
 			   'position' => 'center'
			]);
    	}
	}

	public function render()
    {
        return view('admin::livewire.builder.form');
    }
}

?>

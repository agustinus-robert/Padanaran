<?php

namespace Modules\Admin\Http\Requests\Builder\Form;

use Illuminate\Validation\Rules\Enum;
use App\Http\Requests\FormRequest;
use App\Models\Contract;
use App\Enums\WorkLocationEnum;
use App\Models\Post;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    private $form;

    public function __construct($form){
        $this->form = $form;
    }

    public function authorize()
    {
        return $this->user()->can('store', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $arr = [];
        $arr['title'] = 'required';
        foreach($this->form as $k => $val){
            $arr['arr.'.$k.'.label'] = 'required';
            $arr['arr.'.$k.'.type'] = 'required';
        }

        return $arr;
    }

    public function messages(): array {
       
        $arr = [];
        $arr['title'] = 'Title Tidak boleh Kosong';
        foreach($this->form as $k => $val){
            $arr['arr.'.$k.'.label'] = 'Label tidak boleh kosong';
            $arr['arr.'.$k.'.type'] = 'Tipe tidak boleh kosong';
        }

        return $arr;
    }

    /**
     * Transform request into expected output.
     */
    public function transform()
    {
        return $this->validated();
    }
}

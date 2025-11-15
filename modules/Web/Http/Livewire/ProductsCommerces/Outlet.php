<?php

namespace Modules\Web\Http\Livewire\ProductsCommerces;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Pos\Models\Outlet as Outlets;
use Modules\Admin\Models\Post;
use Livewire\Attributes\On;
use DB;
use Illuminate\Support\Facades\Session;

class Outlet extends Component
{


    public function mount() {}

    public function redirectOutlet($outlet_id)
    {
        $countaPost = Post::where('outlet_id', $outlet_id)->count();
        $outlets = Outlets::find($outlet_id)->first();
        if ($countaPost > 0) {
            session()->put('selected_outlet_on', [
                'outlet_id' => $outlet_id
            ]);

            return redirect()->to(route('web::home.page'));
        } else {
            return redirect()->to(route('web::home.page'))
                ->with('selected_outlet', $outlets->name . '  tidak memiliki E-commerce');
        }
    }

    public function render()
    {
        $data['outlets'] = Outlets::whereNull('deleted_at')->get();

        return view('web::livewire.products.index-outlet', $data);
    }
}

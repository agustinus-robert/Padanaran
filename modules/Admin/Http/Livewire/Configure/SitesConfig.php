<?php

namespace Modules\Admin\Http\Livewire\Configure;

use DB;
use Livewire\Component;

class SitesConfig extends Component
{
    public $sites_name;
    public $location;
    public $coordinate;
    public $email;
    public $calle;
    public $twitter;
    public $facebook;
    public $instagram;
    public $skype;
    public $linkedin;

    public function mount($id = 1){
        $pst = DB::table('post_site_configuration')->where('id', $id)->get()->first();
        $this->sites_name = (!empty($pst->site_name) ? $pst->site_name : '');
        $this->location = (!empty($pst->location) ? $pst->location : '');
        $this->coordinate = (!empty($pst->coordinate) ? $pst->coordinate : '');
        $this->email = (!empty($pst->email) ? $pst->email : '');
        $this->calle = (!empty($pst->call) ? $pst->call : '');
        $this->twitter = (!empty($pst->twitter) ? $pst->twitter : '');
        $this->facebook = (!empty($pst->facebook) ? $pst->facebook : '');
        $this->instagram = (!empty($pst->instagram) ? $pst->instagram : '');
        $this->skype = (!empty($pst->skype) ? $pst->skype : '');
        $this->linkedin = (!empty($pst->linkedin) ? $pst->linkedin : '');
    }

    public function submitForm(){
        preg_match('/src="([^"]+)"/', $this->coordinate, $match);


        DB::table('post_site_configuration')->where('id', 1)->update([
            'site_name' => $this->sites_name,
            'location' => $this->location,
            'email' => $this->email,
            'call' => $this->calle,
            'coordinate' => (isset($match[1]) ? $match[1] : $this->coordinate),
            'twitter' => $this->twitter,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'skype' => $this->skype,
            'linkedin' => $this->linkedin
        ]);

        return redirect(\Request::server('HTTP_REFERER'))->with('msg', "Data berhasil disimpan");
    }

    public function render()
    {
        return view('admin::livewire.configure.sites-config');
    }
}

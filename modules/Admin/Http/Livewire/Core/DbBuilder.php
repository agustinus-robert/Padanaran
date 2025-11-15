<?php

namespace App\Livewire\Core;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use DB;

class DbBuilder extends Component
{

    public $domain_name;
    public $database_name;
    public $id;
    public $env_edit;

    public function mount($id = ''){
       if(!empty($id)){
           $database = DB::connection('mysql2')->table('databases_lists')->where('id', $id)->get()->first();

           $this->domain_name = $database->domain;
           $this->database_name = $database->db_name;
           $this->env_edit = file_get_contents('.env.'.$this->domain_name);
           $this->id = $id;
        }
    }

    public function submitForm(){
        $status = false;

        $cek_domain = DB::connection('mysql2')->table('databases_lists')->where(['domain' => $this->domain_name, 'deleted_at' => null])->first();
        $cek_database = DB::connection('mysql2')->table('databases_lists')->where(['db_name' => $this->database_name, 'deleted_at' => null])->first();
        $is_same_database = DB::connection('mysql2')->table('databases_lists')->where(['id' => $this->id])->first();

        if(!empty($is_same_database)){
            if(isset($cek_domain->id) && isset($is_same_database->id) && $cek_domain->id !== $is_same_database->id){
                $this->alert('warning', 'Domain sudah didaftarkan');
            }

            if(isset($cek_database->id) && isset($is_same_database->id) && $cek_database->id !== $is_same_database->id){
                $this->alert('warning', 'Database telah didaftarkan');
            }

             if(!empty($this->id)){
                DB::connection('mysql2')->table('databases_lists')
                ->where('id', '=', $this->id)
                ->update([
                    'domain' => $this->domain_name,
                    'db_name' => $this->database_name,
                    'env_name' => '.env.'.$this->domain_name
                ]);
                $status = true;

                unlink('.env.'.$this->domain_name);
                file_put_contents('.env.'.$this->domain_name, rtrim($this->env_edit, "\t"));

                if($is_same_database->db_name !== $this->database_name){
                    if(env('OS') == 'windows'){
                        exec('mysqladmin --user="root" --password="" drop '.$is_same_database->db_name);
                        exec('mysql --user="root" --password="" -e "create database '.$this->database_name.'"');
                        exec('mysql  --user="root" --password="" '.$this->database_name.' < '.base_path('cms.sql'));
                    } else {
                        shell_exec('/usr/bin/mysqladmin --user="mysql" --password="" drop '.$is_same_database->db_name);
                        shell_exec('/usr/bin/mysql --user="mysql" --password="" -e "create database '.$this->database_name.'"');
                        shell_exec('/usr/bin/mysql  --user="mysql" --password="" '.$this->database_name.' < '.base_path('cms.sql'));
                        shell_exec('/etc/init.d/apache2 restart');
                    }
                }


                if($status == true){
                    session()->flash('msg', "Database pada domain ".$this->domain_name." telah diperbarui");
                    return redirect(route('dbuildidx'));
                }
            }
        } else if(!empty($cek_domain->domain)){
            $this->alert('warning', 'Domain sudah didaftarkan');
        } else if(!empty($cek_database->db_name)){
            $this->alert('warning', 'Database telah didaftarkan');
        } else {

            DB::connection('mysql2')->table('databases_lists')
            ->insert([
                'domain' => $this->domain_name,
                'db_name' => $this->database_name,
                'env_name' => '.env.'.$this->domain_name
            ]);

            if(env('OS') == 'windows'){
                file_put_contents('.env.'.$this->domain_name, rtrim($this->env_edit, "\t"));
                exec('mysql --user="root" --password="" -e "create database '.$this->database_name.'"');
                exec('mysql  --user="root" --password="" '.$this->database_name.' < '.base_path('cms.sql'));
            } else {
                shell_exec('/usr/bin/mysql --user="mysql" --password="" -e "create database '.$this->database_name.'"');
                shell_exec('/usr/bin/mysql  --user="mysql" --password="" '.$this->database_name.' < '.base_path('cms.sql'));
                shell_exec('/etc/init.d/apache2 restart');
            }

            $status = true;

            if($status == true){
                session()->flash('msg', "Database pada domain ".$this->domain_name." telah didaftarkan");
                return redirect(route('dbuildidx'));
            }
        }
    }


    public function render()
    {
        return view('livewire.core.db-builder')
          ->extends('components.layouts.app_db_builder')
          ->section('konten');
    }
}

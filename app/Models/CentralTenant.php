<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Metable\Metable;

class CentralTenant extends Model
{
    use metable;

    protected $connection = 'pgsql'; 
    protected $table = 'central_tenants';

    protected $fillable = [
        'name',      
        'email',    
        'subdomain',     
        'database',      
        'name',   
        'password',   
        'status',     
    ];

    // Bisa pakai timestamp
    public $timestamps = true;

    /**
     * Contoh relasi: tiap tenant bisa punya banyak user
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}

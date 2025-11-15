<?php

namespace Modules\Admin\Models;
use App\Models\Traits\Restorable\Restorable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactOrganization extends Model
{
    use HasFactory;

    public $table = "contact_organization";
}

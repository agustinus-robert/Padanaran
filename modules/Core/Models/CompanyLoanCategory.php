<?php

namespace Modules\Core\Models;

use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Enums\LoanTypeEnum;

class CompanyLoanCategory extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'cmp_loan_ctgs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'name',
        'description',
        'interest_id',
        'meta'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'type' => LoanTypeEnum::class,
        'meta' => 'object'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'name',
        'description'
    ];

    /**
     * This belongsTo interest.
     */
    public function interest()
    {
        return $this->belongsTo(self::class, 'interest_id')->withDefault()->withTrashed();
    }

    /**
     * Scope when only permanent employee.
     */
    public function scopeWhenOnlyPermanentEmployee($query, $condition = null)
    {
        return $query->when(empty($condition), fn($query) => $query->whereNull('meta->only_permanent_empl')->orWhere('meta->only_permanent_empl', false));
    }
}

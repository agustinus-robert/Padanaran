<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;

class EmployeeLoanInstallmentTransaction extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_loan_inst_transactions';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'installment_id',
        'method',
        'is_cash',
        'amount',
        'payer_id',
        'recipient_id',
        'paid_at',
        'meta',
        'created_at'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'paid_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'installment.loan.employee.user.name',
        'installment.loan.category.name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [];

    /**
     * This belongs to installment.
     */
    public function installment()
    {
        return $this->belongsTo(EmployeeLoanInstallment::class, 'installment_id')->withDefault()->withTrashed();
    }
}

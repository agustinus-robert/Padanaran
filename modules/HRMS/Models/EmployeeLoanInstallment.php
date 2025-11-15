<?php

namespace Modules\HRMS\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Searchable\Searchable;

class EmployeeLoanInstallment extends Model
{
    use Restorable, Searchable;

    /**
     * The table associated with the model.
     */
    protected $table = 'empl_loan_installments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'loan_id',
        'bill_at',
        'paid_off_at',
        'amount',
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
        'bill_at',
        'paid_off_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are searchable.
     */
    public $searchable = [
        'kd',
        'loan.employee.user.name',
        'loan.category.name'
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = [
        'status'
    ];

    /**
     * Get status attribute.
     */
    public function getStatusAttribute()
    {
        return match (true) {
            is_null($this->paid_off_at) && $this->transactions->sum('amount') < $this->amount => 'Belum lunas',
            is_null($this->paid_off_at) && $this->transactions->sum('amount') >= $this->amount => 'Dalam proses',
            $this->paid_off_at && $this->transactions->sum('amount') < $this->amount => 'Belum Lunas',
            $this->paid_off_at && $this->transactions->sum('amount') >= $this->amount => 'Lunas'
        };
    }

    /**
     * This belongs to loan.
     */
    public function loan()
    {
        return $this->belongsTo(EmployeeLoan::class, 'loan_id')->withDefault()->withTrashed();
    }

    /**
     * This has many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(EmployeeLoanInstallmentTransaction::class, 'installment_id');
    }
}

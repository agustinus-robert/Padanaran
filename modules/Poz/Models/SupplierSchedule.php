<?php

namespace Modules\Poz\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Restorable\Restorable;
use App\Models\Traits\Userstamps\Userstamps;
use Modules\Core\Enums\SupplierWorkEnum;

class SupplierSchedule extends Model
{
    use HasFactory, SoftDeletes, Userstamps, Restorable;

    protected $table = 'supplier_schedule';

    protected $fillable = [
        'supplier_id',
        'product_id',
        // 'day',    
        'time'     
    ];

    /**
     * Relationship ke Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getSchedule($product_id)
    {
        $schedules = SupplierSchedule::where('product_id', $product_id)->get()->map(function ($item) {
            $enum = SupplierWorkEnum::fromKey($item->time);

            return [
                'id' => $item->id,
                'supplier_id' => $item->supplier_id,
                'product_id' => $item->product_id,
                // 'day' => $item->day,
                'time' => $item->time,
                'time_label' => $enum?->label(),
                'start_time' => $enum?->startTime(),
                'end_time' => $enum?->endTime(),
                'service_label' => $enum?->serviceLabel(),
            ];
        });

        return response()->json($schedules);
    }

    /**
     * Relationship ke Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

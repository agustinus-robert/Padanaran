<?php

namespace Modules\Portal\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\HRMS\Enums\WorkShiftEnum;

trait ScheduleRepository
{
    /**
     * iterate schedule.
     */
    public function renderSchedule(Collection $data, $start_at, $end_at)
    {
        // iterate schedule
        $x = $data->pluck('dates')
            ->mapWithKeys(fn ($v) => $v)
            ->filter(fn ($v, $k) => Carbon::parse($start_at)->lte(Carbon::parse($k)) && Carbon::parse($end_at)->gte(Carbon::parse($k)));
        return $x;
    }

    /**
     * iterate schedule.
     */
    public function iterateSchedule(Collection $data)
    {
        // iterate schedule
        $x = [];
        foreach ($data as $date => $shifts) {
            foreach ($shifts as $key => $shift) {
                $workshift = WorkShiftEnum::tryFrom($key + 1);
                if (array_filter($shift)) {
                    $x[$date][] = (object) [
                        'in' => Carbon::parse($shift[0])->format('H:i'),
                        'out' => Carbon::parse($shift[1])->format('H:i'),
                    ];
                }
            }
        }
        return $x;
    }

    /**
     * iterate schedule.
     */
    public function countSchedule(Collection $data)
    {
        // count schedule
        return count(collect($this->iterateSchedule($data))->flatten(1));
    }
}

<?php

namespace App\Jobs;

use App\Events\DataSynced;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Models\AcademicClassroom; 
use Modules\Academic\Models\StudentSemester;
use Modules\Academic\Models\AcademicSubject;
use Modules\Academic\Models\AcademicSubjectMeet;
use Modules\Administration\Models\SchoolBillStudent;

class SyncDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $semesterId;
    protected $targetSemesterId;

    public function __construct($semesterId, $targetSemesterId)
    {
        $this->semesterId = $semesterId;
        $this->targetSemesterId = $targetSemesterId;
    }

    public function handle()
    {
        try {
            DB::transaction(function () {
                $this->syncTask('Classroom', AcademicClassroom::class, 'semester_id');
                $this->syncTask('Student', StudentSemester::class, 'semester_id');
                $this->syncTask('Mata Pelajaran', AcademicSubject::class, 'semester_id');
                $this->syncTask('Pertemuan', AcademicSubjectMeet::class, 'semester_id');
                $this->syncTask('Tagihan Siswa', SchoolBillStudent::class, 'smt_id');
            });

            event(new DataSynced([
                'status' => 'completed',
                'type' => 'Selesai',
                'percentage' => 100,
                'message' => 'Seluruh data berhasil disinkronkan!'
            ]));

        } catch (\Exception $e) {
            event(new DataSynced([
                'status' => 'error',
                'type' => 'Gagal',
                'message' => $e->getMessage()
            ]));
            throw $e;
        }
    }

    /**
     * Fungsi Helper agar kode lebih bersih (Refactored)
     */
    private function syncTask($label, $modelClass, $columnId)
    {
        $sourceItems = $modelClass::where($columnId, $this->semesterId)->get();
        
        if ($sourceItems->isEmpty()) {
            event(new DataSynced([
                'status' => 'processing',
                'type' => $label,
                'current' => 0,
                'total' => 0,
                'percentage' => 100,
                'message' => 'Tidak ada data baru'
            ]));
            return;
        }

        $modelClass::where($columnId, $this->targetSemesterId)->delete();
        $total = $sourceItems->count();
        
        foreach ($sourceItems as $key => $item) {
            $new = $item->replicate();
            $new->{$columnId} = $this->targetSemesterId;
            $new->save();

            event(new DataSynced([
                'status' => 'processing',
                'type' => $label,
                'current' => $key + 1,
                'total' => $total,
                'percentage' => round((($key + 1) / $total) * 100)
            ]));
        }
    }
}
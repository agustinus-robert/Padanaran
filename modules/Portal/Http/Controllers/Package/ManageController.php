<?php

namespace Modules\Portal\Http\Controllers\Package;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Academic\Models\StudentsPackage;
use Modules\Academic\Models\Student;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Portal\Notifications\Package\PackageNotification;

class ManageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employee     = $request->user()->employee;
        $month         = Carbon::parse($request->get('month', now()));

        $start_at     = $month->copy()->startOfMonth()->format("Y-m-d");
        $end_at     = $month->copy()->endOfMonth()->format("Y-m-d");
        $packages = StudentsPackage::with('student')->
        whereHas('student', function($query){
            $query->where('grade_id', userGrades());
        })->whereNull('deleted_at')->get();

        $students = Student::where('grade_id', userGrades())->whereNull('deleted_at')->get();
        $packagesCount = StudentsPackage::whereHas('student', function($query){
            $query->where('grade_id', userGrades());
        })->whereNull('deleted_at')->count();

        return view('portal::package.index', compact('start_at', 'end_at', 'packages', 'packagesCount', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'student_id' => $request->input('student_id'),
            'status' => $request->input('status')
        ];

        if($package = StudentsPackage::create($data)){
            Student::find($request->input('student_id'))->user->notify(new PackageNotification($package));
            return redirect()->next()->with('success', 'Terima kasih paket telah disimpan');
        }

        return redirect()->back()->with('error', 'Maaf, terjadi kesalahan saat menyimpan paket');
    }

    public function update(Request $request, StudentsPackage $manage)
    {
        $validated = $request->validate([
            'name' => 'required',
            'status' => 'required',
            'student_id' => 'required',
        ]);

        if ($manage->update($validated)) {
            return redirect()->back()->with('success', 'Paket berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Paket gagal disimpan.');
    }
}

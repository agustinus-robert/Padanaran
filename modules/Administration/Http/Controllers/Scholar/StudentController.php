<?php

namespace Modules\Administration\Http\Controllers\Scholar;

use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Administration\Http\Controllers\Controller;

use Illuminate\Support\Facades\Cache;
use App\Models\References\Hobby;
use App\Models\References\Desire;
use Modules\Account\Models\User;
use Modules\Academic\Models\Academic;
use Modules\Academic\Models\Student;
use Modules\Academic\Excel\Exports\StudentExport;
use Modules\Academic\Excel\Imports\StudentImport;
use Modules\Administration\Http\Requests\Scholar\Student\StoreRequest;
use Modules\Administration\Http\Requests\Scholar\Student\UpdateRequest;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', Student::class);

        $trashed = $request->get('trash');
        $search  = $request->get('search');
        $limit   = $request->get('limit', 10);
        $page    = $request->get('page', 1);
        $gradeId = userGrades();

        $cacheKey = "students:"
            . "grade={$gradeId}:"
            . "search=" . ($search ?: 'null') . ":"
            . "trashed=" . ($trashed ?: 'false') . ":"
            . "limit={$limit}:"
            . "page={$page}";

        $cacheCountKey = "students_count:grade={$gradeId}";

        $students = Student::with('user', 'generation')
                ->search($search)
                ->when($trashed, fn($query) => $query->onlyTrashed())
                ->orderByDesc('id')
                ->paginate($limit);


        $students_count = Student::count();

        return view('administration::scholar.students.index', compact('students', 'students_count'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', Student::class);

        $acdmcs  = Academic::orderByDesc('id')->get();
        $hobbies = Hobby::all();
        $desires = Desire::all();

        $student = null; // karena ini create

        return view('administration::scholar.students.form', compact('acdmcs', 'hobbies', 'desires', 'student'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('store', Student::class);

        $password = 'password';

        if($student = Student::completeInsert($request->merge([
            'grade_id' => userGrades()
        ]), $password)){
            Auth::user()->log(
                ' Siswa bernama '.$student->user->name.' telah ditambahkan '.
                ' <strong>[ID: ' . $student->id . ']</strong>',
                Student::class,
                $student->id
            );

            return redirect($request->get('next', url()->previous()))->with('success', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->user->username.')</strong> berhasil dibuat dengan sandi <strong>'.$password.'</strong>');
        }

        return redirect($request->get('next', url()->previous()))->with('danger', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->user->username.')</strong> gagal dibuat');
    }

    /**
     * Show the specified resource.
     */
    public function show(Student $student)
    {
        $this->authorize('show', Student::class);

        if($student->trashed() || $student->id == auth()->id()) abort(404);

        $student = $student->load('user', 'semesters');

        $acdmcs  = Academic::orderByDesc('id')->get();
        $hobbies = Hobby::all();
        $desires = Desire::all();

        return view('administration::scholar.students.form', compact('student', 'acdmcs', 'hobbies', 'desires'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Student $student)
    {
        $this->authorize('update', Student::class);

        if($student->trashed() || $student->id == auth()->id()) abort(404);

        if($student = Student::completeUpdate($student, $request->merge([
            'grade_id' => userGrades()
        ]))){
            Auth::user()->log(
                ' Siswa bernama '.$student->user->name.' telah diperbarui '.
                ' <strong>[ID: ' . $student->id . ']</strong>',
                Student::class,
                $student->id
            );

            return redirect()->back()->with('success', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->user->username.')</strong> berhasil diperbarui');
        }

        return redirect()->back()->with('danger', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->user->username.')</strong> gagal diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $this->authorize('destroy', Student::class);

        // $this->authorize('remove', $student);
        if($student->delete()){
            Auth::user()->log(
                ' Siswa bernama '.$student->user->name.' telah dihapus '.
                ' <strong>[ID: ' . $student->id . ']</strong>',
                Student::class,
                $student->id
            );

            return redirect()->back()->with('success', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->nis.')</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->nis.')</strong> gagal dihapus');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Student $student)
    {
        $this->authorize('restore', Student::class);

        // $this->authorize('delete', $student);

        $student->restore();

        return redirect()->back()->with('success', 'Siswa atas nama <strong>'.$student->user->profile->name.' ('.$student->nis.')</strong> berhasil dipulihkan');
    }

    /**
     * Kill the specified resource from storage.
     */
    public function kill(Student $student)
    {
        $this->authorize('kill', Student::class);

        // $this->authorize('delete', $student);

        $tmp = $student;
        $student->forceDelete();

        return redirect()->back()->with('success', 'Siswa atas nama <strong>'.$tmp->user->profile->name.' ('.$tmp->nis.')</strong> berhasil dihapus permanen dari sistem');
    }

    /**
     * Exporting data.
     */
    public function export()
    {
        $this->authorize('access', Student::class);

        return Excel::download(new StudentExport, 'template-students.xlsx');
    }

    /**
     * Importing data.
     */
    public function import(Request $request)
    {
        $this->authorize('access', Student::class);

        $import = new StudentImport();
        try {
            $import->import($request->file('file'));

            return redirect()->back()->with('success', 'Impor siswa sukses');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failure = $e->failures()[0];

            return redirect()->back()->withErrors(['file' => 'Error di ROW:'.$failure->row().', '.$failure->errors()[0]]);
        }
    }
}

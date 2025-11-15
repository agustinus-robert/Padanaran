<?php

namespace Modules\Core\Http\Controllers\AdminExtra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles; // ambil semua role milik user

        return view('core::admin_extra.education', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'education_id' => 'required',
        ]);

        $request->session()->put('selected_grade', $request->education_id);
        return redirect()->back()->with('status', 'Jenjang pendidikan berhasil dirubah!');
    }
}

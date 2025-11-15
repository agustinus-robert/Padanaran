<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChooseEducationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roles = $user->roles; // ambil semua role milik user

        return view('auth.choose-education', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'education_id' => 'required',
        ]);

        $request->session()->put('selected_grade', $request->education_id);
        return redirect()->route('portal::dashboard-msdm.index');
    }
}

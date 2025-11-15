<?php

namespace Modules\Admin\Http\Controllers;

use Modules\Reference\Http\Controllers\Controller;
use Modules\Admin\Models\Post;
use Modules\Admin\Models\Contact;
use Modules\Admin\Models\CareerData;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

class DashboardCmsController extends Controller
{
    /**
     * Show the dashboard page.
     */
    public function index()
    {
        $this->authorize('access', Post::class);

        $data['karir'] = Post::where('menu_id', 1812306856258730)->get();
        $data['contact'] = Contact::get();
        $data['karirData'] = CareerData::orderBy('id', 'desc')->take(10)->get();
        // Ambil data pengunjung berdasarkan tanggal
        $visitors = Visitor::select(
            DB::raw('DATE_FORMAT(created_at, "%Y") as year'), // Mengambil tahun
            DB::raw('MONTHNAME(created_at) as month'), // Mengambil nama bulan
            DB::raw('count(*) as total')
        )
            ->groupBy('year', 'month') // Mengelompokkan berdasarkan tahun dan bulan
            ->orderBy('year') // Mengurutkan berdasarkan tahun
            ->orderByRaw('MONTH(created_at)') // Mengurutkan berdasarkan bulan
            ->get();

        $visitors = Visitor::select(
            DB::raw('DATE_FORMAT(created_at, "%Y") as year'),
            DB::raw('MONTHNAME(created_at) as month'),
            DB::raw('count(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        $months = [];
        $totals = [];

        // Ambil data untuk grafik
        foreach ($visitors as $visitor) {
            $months[] = $visitor->month . ' ' . $visitor->year; // Gabungkan nama bulan dengan tahun
            $totals[] = $visitor->total; // Simpan total pengunjung
        }

        // Ambil daftar tahun unik untuk filter
        $years = Visitor::select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year')
            ->pluck('year');

        // Siapkan data untuk dikirim ke view
        $data['months'] = $months;
        $data['totals'] = $totals;
        $data['years'] = $years;

        return view('admin::cms_dashboard', $data);
    }
}

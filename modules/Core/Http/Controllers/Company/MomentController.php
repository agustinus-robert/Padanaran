<?php

namespace Modules\Core\Http\Controllers\Company;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Account\Enums\ReligionEnum;
use Modules\Core\Models\CompanyMoment;
use Modules\Core\Enums\MomentTypeEnum;
use Modules\Core\Http\Requests\Company\Moment\StoreRequest;
use Modules\Core\Http\Requests\Company\Moment\UpdateRequest;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Repositories\CompanyMomentRepository;

class MomentController extends Controller
{
    use CompanyMomentRepository;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', CompanyMoment::class);

        $moments = CompanyMoment::whenYear($request->get('year', date('Y')))
            ->search($request->get('search'))
            ->paginate($request->get('limit', 10));

        $moments_count = CompanyMoment::whenYear(date('Y'))->count();

        return view('core::company.moments.index', compact('moments', 'moments_count'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('store', CompanyMoment::class);
        $religions = ReligionEnum::cases();
        $types = MomentTypeEnum::cases();
        return view('core::company.moments.create', compact('types', 'religions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($moment = $this->storeCompanyMoment($request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Hari libur <strong>' . $moment->name . '</strong> telah berhasil dibuat.');
        }
        return redirect()->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyMoment $moment)
    {
        $this->authorize('update', $moment);
        $religions = ReligionEnum::cases();
        $types = MomentTypeEnum::cases();
        return view('core::company.moments.show', compact('moment', 'types', 'religions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyMoment $moment, UpdateRequest $request)
    {
        if ($moment = $this->updateCompanyMoment($moment, $request->transformed()->toArray())) {
            return redirect()->next()->with('success', 'Hari libur <strong>' . $moment->name . '</strong> telah berhasil diperbarui.');
        }
        return redirect()->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyMoment $moment)
    {
        $this->authorize('destroy', $moment);
        if ($moment = $this->destroyCompanyMoment($moment)) {
            return redirect()->next()->with('success', 'Hari libur <strong>' . $moment->name . '</strong> telah berhasil dihapus.');
        }
        return redirect()->fail();
    }

    /**
     * Sync holidays from API.
     */
    public function sync()
    {
        $endpoint = 'https://api-harilibur.vercel.app/api';
        $responses = Http::get($endpoint)->collect();

        foreach ($responses as $respons) {
            $holiday = CompanyMoment::updateOrCreate([
                'name' => $respons['holiday_name'],
                'date' => $respons['holiday_date'],
            ], [
                'type' => $respons['is_national_holiday'] ? 1 : 5,
                'is_holiday' => $respons['is_national_holiday'],
                'meta' => NULL
            ]);
        }
        if ($holiday) {
            return redirect()->next()->with('success', 'Data hari libur berhasil ditambahkan, terima kasih.');
        }
        return redirect()->next()->with('danger', 'Data hari libur gagal ditambahkan, coba beberapa saat lagi!.');
    }
}

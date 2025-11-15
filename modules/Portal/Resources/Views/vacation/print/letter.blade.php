@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <div class="center" style="position: absolute; top: 0; right: 0; width: 80px;">
            @include('x-docs::qr', ['qr' => $document->qr, 'link' => route('docs::verify', ['qr' => $document->qr]), 'withText' => true])
        </div>

        <p>Sleman, {{ strftime('%d %B %Y') }}</p>
        <p>Kepada Yth.,</p>
        <p class="bold">
            @isset($to)
                {{ $to->employee->user->name }} <br>
                {{ $to->position->name }} <br>
                @endif
                PT PEMAD INTERNATIONAL TRANSEARCH
            </p>
            <p>Dengan hormat, <br> Saya yang bertanda tangan di bawah ini:</p>
            <table width="100%">
                @foreach (array_filter([
                'Nama Karyawan' => $vacation->quota->employee->user->name,
                'Nomor Induk Karyawan' => $vacation->quota->employee->kd,
                'Jabatan' => $vacation->quota->employee->position->position->name,
                'Departemen' => $vacation->quota->employee->position->position->department->name,
                'Atasan' => $vacation->quota->employee->position->position->parents->last()?->employees->first()->user->name ?? null,
            ]) as $key => $value)
                    <tr>
                        <td width="28%">{{ $key }}</td>
                        <td width="2%">:</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
            <p>Dengan ini mengajukan permohonan cuti kerja sebagai berikut:</p>
            <table width="100%">
                @foreach (array_filter([
                    'Waktu pengajuan' => Carbon::parse($vacation['created_at'])->isoFormat('LLLL'),                
                    'Tanggal cuti/libur hari raya' => isset(collect($vacation->dates)->first()['cashable'])
                    ? collect($vacation->dates)->count() . ' dikompensasikan'
                    : collect($vacation->dates)->filter(fn($date) => empty($date['f']))->map(fn($date) => strftime('%d %B %Y', strtotime($date['d'])))->join(', '),
                'Tanggal libur sbg freelance' => collect($vacation->dates)->filter(fn($date) => isset($date['f']))->map(fn($date) => strftime('%d %B %Y', strtotime($date['d'])))->join(', '),
                'Alasan cuti' => $vacation->description ?: '-',
            ]) as $key => $value)
                    <tr>
                        <td width="28%">{{ $key }}</td>
                        <td width="2%">:</td>
                        <td>{{ $value }}</td>
                    </tr>
                @endforeach
            </table>
            <p>Demikian surat permohonan ini saya sampaikan, atas perhatiannya saya mengucapkan terima kasih.</p>
            <br>
            @php
                $signatures = [
                    [
                        'position' => 'Karyawan',
                        'qr' => $document->signatures->firstWhere('user_id', $vacation->quota->employee->user->id)?->qr,
                        'name' => $vacation->quota->employee->user->name,
                    ],
                ];
                
                foreach ($vacation->approvables as $approvable) {
                    array_push($signatures, [
                        'position' => $approvable->userable->position->level->label(),
                        'qr' => $document->signatures->firstWhere('user_id', $approvable->userable->employee->user->id)?->qr,
                        'name' => $approvable->userable->employee->user->name,
                    ]);
                }
            @endphp
            <table width="100%">
                <tr>
                    @foreach ($signatures as $value)
                        <td class="center" width="{{ round(100 / count($signatures), 2) }}%">
                            <p>{{ $value['position'] }}</p>
                            <div style="height: 90px;">
                                @include('x-docs::qr', ['qr' => $value['qr'], 'link' => route('docs::verify', ['qr' => $value['qr'], 'type' => 'signature']), 'small' => true, 'withText' => true])
                            </div>
                            <p class="bold">{{ $value['name'] }}</p>
                        </td>
                    @endforeach
                </tr>
            </table>
        </section>
    @endsection

@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -1.25cm; left: -1.25cm; width: 21cm; height: 1.76cm">
        <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.75cm; left: -1.25cm; width: 21cm; height: 1.76cm">
        <div class="center" style="font-size: 12pt; margin-bottom: 20px; margin-top: 25px;">
            <h4 style="margin: 0; font-size: 9pt;">
                SURAT PERMOHONAN PINJAMAN
            </h4>
        </div>
        <div class="center" style="position: absolute; top: 2; right: 0; width: 80px;">
            @include('x-docs::qr', ['qr' => $document->qr, 'link' => route('docs::verify', ['qr' => $document->qr]), 'withText' => true])
        </div>

        <p>Sleman, {{ $loan->created_at->isoFormat('LL') }}</p>
        <p>Kepada Yth.,</p>
        <p class="bold">
            PT PEMAD INTERNATIONAL TRANSEARCH
        </p>
        <p>Dengan hormat, <br> Saya yang bertanda tangan di bawah ini:</p>
        <table width="100%">
            @foreach ([
            'Nama Karyawan' => $loan->employee->user->name,
            'Nama Perusahaan' => 'PT PeMad International Transearch, sebuah badan hukum Perseroan Terbatas yang berkedudukan di Kabupaten/Kota Sleman (“Perusahaan”).',
            'Nomor Induk Karyawan' => $loan->employee->kd,
            'Jabatan' => $loan->employee->position->position->name,
            'Departemen' => $loan->employee->position->position->department->name,
        ] as $key => $value)
                <tr>
                    <td width="28%">{{ $key }}</td>
                    <td width="2%">:</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
            <tr>
                <td>("Karyawan")</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <p>Dengan ini mengajukan permohonan peminjaman uang kepada Perusahaan sebagai berikut:</p>
        <table width="100%">
            @foreach (array_filter([
            'Jenis Pinjaman' => $loan->category->name . ' (' . $loan->category->description . ')',
            'Besar Pinjaman' => 'Rp.' . number_format($loan->amount_total, 0),
            'Terbilang' => strtolower(inwords($loan->amount_total)) . ' rupiah',
            'Keperluan' => $loan->description,
            'Jangka Waktu Pengembalian' => $loan->tenor,
            'Tanggal Penyerahan Pinjaman' => $loan->approved_at ? $loan->approved_at->isoFormat('LL') : '',
            'Tanggal Jatuh Tempo Pelunasan' => $loan->installments->last()->bill_at->isoFormat('LL'),
            'Cara Pengembalian' => 'Pemotongan Gaji',
            'Ketentuan Peminjaman Uang' => '',
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
                    'qr' => $document->signatures->firstWhere('user_id', $loan->employee->user->id)?->qr,
                    'name' => $loan->employee->user->name,
                ],
            ];
            
            foreach ($loan->approvables as $approvable) {
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
                        <p class="fw-normal">{{ $value['name'] }}</p>
                    </td>
                @endforeach
            </tr>
        </table>
    </section>
@endsection

@push('styles')
    <style>
        @page {
            margin: 1.25cm 1.25cm 1.25cm 1.25cm;
            font-size: 10pt;
        }

        table {
            width: 100%;
        }

        p {
            font-size: 10pt;
        }

        td,
        th {
            font-size: 10pt;
            line-height: 14pt;
        }

        .border-y {
            border-top: 1px double #ccc;
            border-bottom: 1px double #ccc;
        }
    </style>
@endpush

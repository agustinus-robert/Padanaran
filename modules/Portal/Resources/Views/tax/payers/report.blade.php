@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
        <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm">
        <div class="center" style="font-size: 12pt; margin-bottom: 20px;">
            <h4 style="margin: 0; font-size: 9pt;">
                FORMULIR DATA WAJIB PAJAK ORANG PRIBADI TAHUN {{ date('Y') }}
            </h4>
        </div>
        @php
            $list = [
                'Nomor Pokok Wajib Pajak (NPWP)' => $user->getMeta('tax_number') ?? '',
                'Alamat sesuai NPWP' => $user->getMeta('tax_address') ?? '',
                'Nama Wajib Pajak' => $user->name ?? '',
                'Status perkawinan' => \Modules\Account\Enums\MariageEnum::tryFrom($user->getMeta('profile_mariage'))->label() ?? '',
                'Jumlah tanggungan (anak)' => $user->getMeta('profile_child'),
                'NIK (Nomor Induk Kependudukan)' => $user->getMeta('profile_nik'),
                'Alamat sesuai KTP' => $user->getMeta('address_address'),
            ];
        @endphp
        <br>
        <div style="font-size: 12pt; margin-top: 20px;">
            <div style="margin-left:50px">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        @foreach ($list as $k => $v)
                            <tr>
                                <td width="10">{{ $loop->iteration }}.</td>
                                <td width="30%">{{ $k }}</td>
                                <td width="5">:</td>
                                <td width="50%"><strong>{{ $v }}</strong></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="100%">
                                Catatan:
                            </td>
                        </tr>
                        <tr>
                            <td colspan="100%">
                                <ol>
                                    <li>Jika tidak mempunyai NPWP dapat dikosongkan.</li>
                                    <li>Jika mempunyai NPWP, dapat mengunggah softfilenya.</li>
                                </ol>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
            <table cellpadding="0 10" cellspacing="0" style="margin: 0; margin-top: -10px">
                <tr>
                    <td style="text-align: center;" colspan="{{ count($signatures) }}">Dibuat oleh,</td>
                </tr>
                <tr>
                    @foreach ($signatures as $value)
                        <td class="center" width="{{ round(100 / count($signatures), 2) }}%">
                            <p style="font-size: 12px;">{{ $value['position'] }}</p>
                            <div style="height: 75px;">
                                @include('x-docs::qr', ['qr' => $value['qr'], 'link' => route('docs::verify', ['qr' => $value['qr'], 'type' => 'signature']), 'small' => true, 'withText' => true])
                            </div>
                            <p style="font-size: 12px;" class="bold">{{ $value['name'] }}</p>
                        </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        @page {
            margin: .75cm .75cm 1.25cm .75cm;
        }

        table {
            width: 100%;
            align: center;
        }

        td,
        th {
            font-size: 9pt;
            line-height: 14pt;
        }

        .border-y {
            border-top: 1px double #ccc;
            border-bottom: 1px double #ccc;
        }
    </style>
@endpush

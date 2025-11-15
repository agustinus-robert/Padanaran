@extends('docs::layouts.pdf')

@section('title', $doc['label'])

@section('content')
    <section>
        <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
        <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm">
        <div class="center" style="font-size: 12pt; margin-bottom: 20px;">
            <h4 style="margin: 0; font-size: 9pt;">
                {{ $doc['label'] }}
            </h4>
        </div>
        <br>
        <div style="font-size: 12pt; margin-top: 20px;">
            <table cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td width="120">Nama dokumen</td>
                        <td width="5">:</td>
                        <td><strong>{{ $doc['label'] }}</strong></td>
                    </tr>
                    <tr>
                        <td>Kategori dokumen</td>
                        <td>:</td>
                        <td><strong>{{ \Modules\Docs\Enums\DocumentTypeEnum::tryFrom($doc['type'])->label() }}</strong></td>
                    </tr>
                    <tr>
                        <td>Tanggal pembuatan</td>
                        <td>:</td>
                        <td><strong>{{ \Carbon\Carbon::parse(date('Y-m-d', strtotime(now())))->isoFormat('LL') }}</strong></td>
                    </tr>
                </tbody>
            </table>
            <h5 style="margin-bottom: 2px;">Isi dokumen {{ \Modules\Docs\Enums\DocumentTypeEnum::tryFrom($doc['type'])->label() }}</h5>
            <div>
                <div style="margin: 0; font-size: 9pt; !important">{!! $text !!}</div>
            </div>
            <h5 style="margin-bottom: 4px;">Keterangan</h5>
            <div>
                <div style="margin: 0; font-size: 9pt; !important">{!! $doc['meta']['description'] !!}</div>
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

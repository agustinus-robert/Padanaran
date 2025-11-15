@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -2.54cm; left: -2.54cm; width: 21cm; height: 1.76cm">
        <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -2.54cm; left: -2.54cm; width: 21cm; height: 1.76cm">
        <div class="center" style="font-size: 12pt; margin-bottom: 20px; margin-top: 25px;">
            <h4 style="margin: 0; font-size: 9pt;">
                FORM PENCATATAN INVENTARIS
            </h4>
        </div>
        <div class="center" style="position: absolute; top: 2; right: 0; width: 80px;">
            @include('x-docs::qr', ['qr' => $document->qr, 'link' => route('docs::verify', ['qr' => $document->qr]), 'withText' => true])
        </div>

        <table width="100%">
            @foreach ([
            'Nama inventaris' => $item->name,
            'Merk' => $item->brand,
            'Nomor inventaris' => $item->meta?->inv_num,
            'Kategori' => $item->meta?->ctg_name,
            'Tanggal pembelian' => $item->bought_at ? $item->bought_at->isoFormat('LL') : '',
            'Harga pembelian' => $item->bought_price ? 'Rp. ' . number_format($item->bought_price) : '',
            'Lokasi' => $item->placeable->name ?? '',
            'Pengguna' => $item->user->name ?? '',
            'Penanggung jawab' => $item->pic->name ?? '',
            'Kondisi' => $item->condition->label() ?? '',
            'Masa pakai' => isset($item->meta?->usefull) ? $item->meta?->usefull . ' Bulan' : '',
            'Masa habis pakai' =>
                isset($item->meta?->usefull) && $item->bought_at
                    ? Carbon::parse($item->bought_at)->addMonths($item->meta?->usefull)->isoFormat('LL')
                    : '',
        ] as $key => $value)
                <tr>
                    <td width="32%">{{ $key }}</td>
                    <td width="2%">:</td>
                    <td>{{ $value }}</td>
                </tr>
            @endforeach
        </table>
        <h5 style="margin-bottom: 8px;">Keterangan</h5>
        <div>
            <div style="margin: 0; font-size: 9pt; !important">{!! $item->meta?->description ?? '' !!}</div>
        </div>
        <h5 style="margin-bottom: 8px;">Penyusutan</h5>
        <table width="100%" cellpadding="4" cellspacing="0" style="margin: 0;">
            <thead>
                <tr style="background: #333; color: #fff;">
                    <td>No</td>
                    <td>Bulan</td>
                    <td>Penyusutan ke</td>
                    <td>Nominal</td>
                    <td>Nilai aset</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($days as $key => $day)
                    <tr style="{{ $loop->index % 2 == 0 ? 'background: #eee;' : '' }}">
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $day['date'] }}</td>
                        <td align="center">{{ $key }}</td>
                        <td>Rp. {{ number_format($day['diff'], 2) }}</td>
                        <td>Rp. {{ number_format($day['nom'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="break"></div>
        <h5 style="margin-bottom: 8px;">Lampiran</h5>
        <div>
            @if (isset($item->attachments->items))
                <div class="d-flex justify-content-between align-self-center">
                    @foreach ($item->attachments->items as $device)
                        <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(Storage::url($device->url))) }}" width="240">
                    @endforeach
                </div>
            @else
                Tidak ada lampiran
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <style>
        @page {
            margin: 2.54cm 2.54cm 2.54cm 2.54cm;
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

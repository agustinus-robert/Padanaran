@extends('layouts.dompdf')

@section('title', $title)

@section('content')
    <section>
        <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -2cm; right: -2cm; width: 21cm; height: 1.76cm">
        <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -.75cm; left: -2cm; right: -2cm; width: 21cm; height: 1.76cm">
        <div class="center" style="font-size: 12pt; margin-bottom: 10px; margin-top: 50px;">
            <h4 style="margin: 0; font-size: 12pt;">
                <u>TANDA TERIMA INVENTARIS MILIK PERUSAHAAN</u>
            </h4>
        </div>
        <div style="font-size: 12pt; margin-top: 5px;">
            <p style="margin-bottom: 0px;">Pada tanggal {{ $manage->created_at->isoFormat('LL') }} telah diterima inventaris milik Perusahaan oleh Karyawan sebagai berikut:</p>
            <table style="margin-bottom: 0px;">
                <tbody>
                    <tr>
                        <td colspan="100%"><strong>Penerima</strong></td>
                    </tr>
                    <tr>
                        <td width="120">Nama</td>
                        <td width="5">:</td>
                        <td>{{ $manage->receiver->name }}</td>
                    </tr>
                    <tr>
                        <td>Nomor induk</td>
                        <td>:</td>
                        <td>{{ $manage->receiver->employee->kd ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>{{ $manage->receiver->employee->position->position->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Departemen</td>
                        <td>:</td>
                        <td>{{ $manage->receiver->employee->position->position->department->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Divisi</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td colspan="100%">
                            <strong>(“Karyawan”)</strong>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%"><strong>Pemberi</strong></td>
                    </tr>
                    <tr>
                        <td width="120">Nama Perusahaan</td>
                        <td width="5">:</td>
                        <td>PT PéMad International Transearch</td>
                    </tr>
                    <tr>
                        <td>Kedudukan</td>
                        <td>:</td>
                        <td>Kabupaten /Kota Sleman</td>
                    </tr>
                    <tr>
                        <td>Diwakili oleh</td>
                        <td>:</td>
                        <td>{{ $manage->giver->employee->position->position->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="100%">
                            <strong>(“Perusahaan”)</strong>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="100%"><strong>Keterangan</strong></td>
                    </tr>
                    <tr>
                        <td width="120">Untuk Keperluan</td>
                        <td width="5">:</td>
                        <td>{{ $manage->meta->for ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
            <h5 style="margin-top: 7px; margin-bottom: 8px; font-size: 11pt;">Inventaris</h5>
            <table cellpadding="4" cellspacing="0" style="margin: 0;">
                <thead>
                    <tr style="background: #333; color: #fff;">
                        <td>No</td>
                        <td>Jenis</td>
                        <td>Nama Inventaris</td>
                        <td>Peminjaman</td>
                        <td>Pengembalian</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manage->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ \Modules\Core\Enums\BorrowableTypeEnum::tryFromInstance($item->modelable_type)->label() }}</td>
                            <td>
                                {{ $item->modelable->name }}
                                @isset($item->modelable->meta->inv_num)
                                    <br>{{ $item->modelable->meta->inv_num ?? '-' }}
                                @endisset
                            </td>
                            <td>{{ !is_null($item->received_at) ? $item->received_at->isoFormat('LL') : '-' }}</td>
                            <td>{{ !is_null($item->returned_at) ? $item->returned_at->isoFormat('LL') : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h5 style="margin-bottom: -10px;">Catatan</h5>
            <div>
                <div style="margin: 0; font-size: 11pt; !important">{!! $text ?? '-' !!}</div>
            </div>
            <br>
            <table cellpadding="0 10" cellspacing="0" style="margin: 0; margin-top: -10px">
                <tr>
                    <td style="text-align: center;" colspan="{{ count($signatures) }}">Disetujui oleh,</td>
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
            margin: .75cm 2cm .75cm 2cm;
        }

        table {
            width: 100%;
            margin: auto;
        }

        td,
        th {
            font-size: 11pt;
            line-height: 18pt;
        }

        .border-y {
            border-top: 1px double #ccc;
            border-bottom: 1px double #ccc;
        }
    </style>
@endpush

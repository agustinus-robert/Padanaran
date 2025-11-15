@extends('layouts.dompdf')

@section('title', $title)

@php($alphabets = range('A', 'Z'))

@section('content')
    <div class="center" style="position: fixed; top: 0.6cm; right: .5cm; width: 80px;">
        @include('x-docs::qr', ['qr' => $document->qr, 'link' => route('docs::verify', ['qr' => $document->qr]), 'size' => 48])
    </div>
    @foreach ($salary->components as $page => $slip)
        @php($sliptotal[$page] = 0)
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">{{ $slip['slip'] }}</h4>
                <div>{{ $salary->name }}</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <table cellpadding="0 10" cellspacing="0" style="margin: 0 -10px;width: 100%;">
                        <tbody>
                            @for ($row = 0; $row < ceil(count($slip['ctgs']) / 4); $row++)
                                <tr>
                                    @for ($i = 0; $i < 4; $i++)
                                        @if ($i != 3)
                                            <td style="width: 33.33%">
                                        @endif
                                        @isset($slip['ctgs'][$row * 3 + $i])
                                            <div>
                                                @php($ctg = $slip['ctgs'][$row * 3 + $i])
                                                @php($componenttotal = 0)
                                                <br>
                                                <div>
                                                    <strong>
                                                        @if (count($slip['ctgs']) > 1)
                                                            {{ $alphabets[$ctg['az'] - 1] }}.
                                                        @endif
                                                        <span>{{ $ctg['ctg'] }}</span>
                                                    </strong>
                                                </div>
                                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                                    <tbody>
                                                        @php($items = array_filter($ctg['i'], fn($i) => isset($i['enable']) && $i['enable']))
                                                        @foreach ($items as $component)
                                                            @php($amount = $component['amount'] * $component['n'])
                                                            @php($x = $component['u'] == 4 ? 2 : 0)
                                                            <tr>
                                                                <td width="7%">{{ count($items) > 1 ? $loop->iteration : '-' }}</td>
                                                                <td width="63%">{{ $component['name'] }}</td>
                                                                <td width="4%">{{ $units[$component['u'] - 1]->prefix() }}</td>
                                                                <td width="26%" style="text-align: right;">{{ number_format($amount, $x, ',', '.') }} {{ $units[$component['u'] - 1]->suffix() }}</td>
                                                            </tr>
                                                            @unless ($units[$component['u'] - 1]->disabledState())
                                                                @php($componenttotal += $amount)
                                                                @php($sliptotal[$page] += $amount)
                                                            @endunless
                                                        @endforeach
                                                        @unless ($units[$component['u'] - 1]->disabledState())
                                                            <tr>
                                                                <td class="border-y" colspan="2"><strong>Jumlah {{ $ctg['ctg'] }}</strong></td>
                                                                <td class="border-y">
                                                                    <strong>Rp</strong>
                                                                </td>
                                                                <td class="border-y" style="text-align: right;"><strong>{{ number_format($componenttotal, 0, ',', '.') }}</strong></td>
                                                            </tr>
                                                        @endunless
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endisset
                                        @if ($i != 2)
                                            </td>
                                        @endif
                                    @endfor
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                    <br>
                    <div class="border-y">
                        <div><cite><strong>Total {{ $slip['slip'] }}: Rp {{ number_format($sliptotal[$page], 0, ',', '.') }} </strong></cite></div>
                        <div><cite>Terbilang: {{ strtolower(inwords($sliptotal[$page])) }} rupiah</cite></div>
                    </div>
                    <br>
                    @if ($loop->last && $salary->components->count() > 1)
                        <div class="border-y">
                            <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                <tr>
                                    <td width="20%"><strong>Total gaji bersih setelah pajak</strong></td>
                                    <td width="5%" style="text-align: right; padding-right: 10px;"> = </td>
                                    <td width="75%"><strong>{{ $salary->components->implode('slip', ' + ') }}</strong></td>
                                </tr>
                                @if (count($sliptotal) > 1)
                                    <tr>
                                        <td></td>
                                        <td style="text-align: right; padding-right: 10px;"> = </td>
                                        <td><strong>{{ implode(' + ', array_map(fn($t) => ($t < 0 ? '(' : '') . 'Rp ' . number_format($t, 0, ',', '.') . ($t < 0 ? ')' : ''), $sliptotal)) }}</strong></td>
                                    </tr>
                                @endif
                                <tr>
                                    <td></td>
                                    <td style="text-align: right; padding-right: 10px;"> = </td>
                                    <td><strong>Rp {{ number_format(array_sum($sliptotal), 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                            <div><cite>Terbilang: {{ strtolower(inwords(array_sum($sliptotal))) }} rupiah</cite></div>
                            {{-- <div><cite>Catatan: {{ strtolower($salary->description ?? '') }}</cite></div> --}}
                        </div>
                    @endif
                    <footer style="position: fixed; bottom:0; width: 100%;">
                        <div class="center">Sleman, {{ $salary->released_at?->isoFormat('LL') ?? now()->isoFormat('LL') }}</div>
                        <table class="center" cellpadding="0" cellspacing="0" style="width: 100%;">
                            <tr>
                                <td width="25%">Dibuat oleh:</td>
                                <td width="25%">Diterbitkan oleh:</td>
                                <td width="25%">Disetujui oleh:</td>
                                <td width="25%">Diterima oleh:</td>
                            </tr>
                            <tr>
                                <td>
                                    @include('x-docs::qr', ['qr' => $document->signatures[0]->qr, 'link' => route('docs::verify', ['qr' => $document->signatures[0]->qr, 'type' => 'signature']), 'size' => 48])
                                </td>
                                <td>
                                    @if ($salary->validated_at)
                                        @include('x-docs::qr', ['qr' => $document->signatures[1]->qr, 'link' => route('docs::verify', ['qr' => $document->signatures[1]->qr, 'type' => 'signature']), 'size' => 48])
                                    @endif
                                </td>
                                <td>
                                    @if ($salary->approved_at)
                                        @include('x-docs::qr', ['qr' => $document->signatures[2]->qr, 'link' => route('docs::verify', ['qr' => $document->signatures[2]->qr, 'type' => 'signature']), 'size' => 48])
                                    @endif
                                </td>
                                <td>
                                    @if ($salary->accepted_at)
                                        @php($selfsign = isset($document->signatures[3]) ? $document->signatures[3] : $document->signatures->firstWhere('user_id', $salary->employee->user_id))
                                        @include('x-docs::qr', ['qr' => $selfsign->qr, 'link' => route('docs::verify', ['qr' => $selfsign->qr, 'type' => 'signature']), 'size' => 48])
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                @foreach ($document->signatures->take(3) as $signature)
                                    <td>
                                        <div><strong>{{ $signature->user->name }}</strong></div>
                                        <div style="font-size:5pt;">{{ $signature->user->employee->position->position->name }}</div>
                                    </td>
                                @endforeach
                                <td>
                                    <div><strong>{{ $salary->employee->user->name }}</strong></div>
                                    <div style="font-size:5pt;">{{ $salary->employee->position->position->name }}</div>
                                </td>
                            </tr>
                        </table>
                    </footer>
                </div>
            </div>
        </section>
        @if (!$loop->last)
            <div class="break"></div>
        @endif
    @endforeach
    @isset($attachments['outworks'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Insentif Kegiatan</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Kategori</strong></td>
                            <td><strong>Nama kegiatan</strong></td>
                            <td><strong>Waktu pelaksanaan</strong></td>
                            <td><strong>Total Durasi</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['outworks'] as $outwork)
                            @php($subtotal = collect($outwork['amount'])->sum('total'))
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">{{ $outwork['category'] }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $outwork['name'] }}</strong></div>
                                    <div style="color:#777;">{{ Str::words($outwork['description'], 10) }}</div>
                                </td>
                                <td class="border-y">
                                    <ul style="margin: 0; padding:0; list-style: none;">
                                        @foreach ($outwork['dates'] as $i => $date)
                                            <li style="padding:0;"><strong>{{ $date['d'] }}</strong> pukul {{ $date['t_s'] }} s.d. {{ $date['t_e'] }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="border-y">
                                    {{ collect($outwork['amount'])->sum('inside.hour') + collect($outwork['amount'])->sum('outside.hour') }} jam
                                </td>
                                <td class="border-y">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $subtotal)
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total Insentif Kegiatan: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['reimbursements'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Reimbursement</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Kategori</strong></td>
                            <td><strong>Keterangan</strong></td>
                            <td><strong>Diajukan pada</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['reimbursements'] as $reimbursement)
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">{{ $reimbursement['category'] }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $reimbursement['name'] }}</strong></div>
                                    <div style="color:#777;">{{ Str::words($reimbursement['description'], 10) }}</div>
                                </td>
                                <td class="border-y">{{ $reimbursement['submitted_at'] }}</td>
                                <td class="border-y">Rp {{ number_format($reimbursement['amount'], 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $reimbursement['amount'])
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total Reimbursement: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['overtimes'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Lembur</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Nama kegiatan</strong></td>
                            <td><strong>Waktu pelaksanaan</strong></td>
                            <td><strong>Total Durasi</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['overtimes'] as $overtime)
                            @php($subtotal = collect($overtime['amount'])->sum('total'))
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $overtime['name'] }}</strong></div>
                                    {{-- <div style="color:#777;">{{ Str::words($overtime['description'], 5, '...') }}</div> --}}
                                </td>
                                <td class="border-y">
                                    <ul style="margin: 0; padding:0; list-style: none;">
                                        @foreach ($overtime['dates'] as $i => $date)
                                            <li style="padding:0;"><strong>{{ $date['d'] }}</strong> pukul {{ $date['t_s'] }} s.d. {{ $date['t_e'] }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="border-y">
                                    {{ collect($overtime['amount'])->sum('hour') }} jam
                                </td>
                                <td class="border-y">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $subtotal)
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total lembur: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['poms'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Personnel of the month</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Periode</strong></td>
                            <td><strong>Keterangan</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['poms'] as $pom)
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">{{ $pom['period'] }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $pom['name'] }}</strong></div>
                                    <div style="color:#777;">{{ Str::words($pom['description'], 10) }}</div>
                                </td>
                                <td class="border-y">Rp {{ number_format($pom['amount'], 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $pom['amount'])
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total Personnel of the month: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['additional'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Pekerjaan tambahan</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Periode</strong></td>
                            <td><strong>Keterangan</strong></td>
                            <td><strong>Tarif</strong></td>
                            <td><strong>Makan</strong></td>
                            <td><strong>Transport</strong></td>
                            <td><strong>Tambahan</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['additional'] as $adt)
                            @php($rate = collect($adt['amount'])->sum('rate'))
                            @php($meal = collect($adt['amount'])->sum('meal'))
                            @php($transport = collect($adt['amount'])->sum('transport'))
                            @php($load = collect($adt['amount'])->sum('load'))
                            @php($subtotal = collect($adt['amount'])->sum('total'))
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">{{ $adt['period'] }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $adt['name'] }}</strong></div>
                                    <div style="color:#777;">{{ Str::words($adt['description'], 10) }}</div>
                                </td>
                                <td class="border-y">Rp {{ number_format($rate, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($meal, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($transport, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($load, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $subtotal)
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total pekerjaan tambahan: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['additional_payment'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/kop-a4-portrait-header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            {{-- <img src="{{ asset('img/logo/kop-a4-portrait-footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm"> --}}
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Pekerjaan tambahan</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Keterangan</strong></td>
                            <td><strong>Jam</strong></td>
                            <td><strong>Tarif</strong></td>
                            <td><strong>Makan</strong></td>
                            <td><strong>Transport</strong></td>
                            <td><strong>Tambahan</strong></td>
                            <td><strong>Nominal</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($total = 0)
                        @foreach ($attachments['additional_payment'] as $adt)
                            @php($rate = collect($adt['amount'])->sum('rate'))
                            @php($meal = collect($adt['amount'])->sum('meal'))
                            @php($transport = collect($adt['amount'])->sum('transport'))
                            @php($load = collect($adt['amount'])->sum('load'))
                            @php($subtotal = collect($adt['amount'])->sum('total'))
                            @php($hour = collect($adt['amount'])->sum('hour_in') + collect($adt['amount'])->sum('hour_out'))
                            <tr>
                                <td class="border-y">{{ $loop->iteration }}</td>
                                <td class="border-y">
                                    <div><strong>{{ $adt['name'] }}</strong></div>
                                    <div style="color:#777;">{{ Str::words($adt['description'], 10) }}</div>
                                </td>
                                <td class="border-y">{{ number_format($hour, 2, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($rate, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($meal, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($transport, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($load, 0, ',', '.') }}</td>
                                <td class="border-y">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @php($total += $subtotal)
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total pekerjaan tambahan: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['coordinator_student'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            <img src="{{ asset('img/logo/footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm">
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Lampiran rekapitulasi koordinator</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Nama</strong></td>
                            <td><strong>Jam</strong></td>
                            <td><strong>Jumlah</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @php($total = 0)
                        @foreach ($attachments['coordinator_student'] as $value)
                            @php($value->name)
                            @php($value->hourly)
                            @php($value->price)
                            @php($total += $value->hourly * $value->price)


                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->hourly }}</td>
                                <td>Rp {{ number_format($value->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total pekerjaan tambahan: Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
    @isset($attachments['deduction'])
        <div class="break"></div>
        <section>
            <img src="{{ asset('img/logo/header.png') }}" alt="" style="position:fixed; top: -.75cm; left: -.75cm; width: 21cm; height: 1.76cm">
            <img src="{{ asset('img/logo/footer.png') }}" alt="" style="position:fixed; bottom: -1.5cm; left: -.75cm; width: 21cm; height: 1.76cm">
            <div class="center" style="font-size: 7pt;">
                <h4 style="margin: 0; font-size: 9pt;">Lampiran</h4>
                <div>Lampiran rekapitulasi potongan</div>
                @unless ($salary->start_at->isSameDay($salary->end_at))
                    <div>
                        <span>Periode</span> {{ $salary->start_at->isoFormat('LL') }} s.d. {{ $salary->end_at->isoFormat('LL') }}
                    </div>
                @endunless
            </div>
            <br>
            <div style="font-size: 7pt;">
                <table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td width="60">Nama</td>
                            <td width="5">:</td>
                            <td><strong>{{ $salary->employee->user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td>Jabatan</td>
                            <td>:</td>
                            <td><strong>{{ $salary->employee->position->position->name }}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <table cellpadding="4 0" cellspacing="0" style="width: 100%;">
                    <thead>
                        <tr>
                            <td style="max-width: 24px; text-align:left;"><strong>No</strong></td>
                            <td><strong>Keterangan</strong></td>
                            <td><strong>Tarif</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        @php($i = 1)
                        @php($total = 0)
                        @foreach ($attachments['deduction'] as $value)
                            @php($total += $value->price)


                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $value->name }}</td>
                                <td>Rp {{ number_format($value->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
                <div>
                    <div><cite><strong>Total potongan : Rp {{ number_format($total, 0, ',', '.') }} </strong></cite></div>
                    <div><cite>Terbilang: {{ strtolower(inwords($total)) }} rupiah</cite></div>
                </div>
            </div>
        </section>
    @endisset
@endsection

@push('styles')
    <style>
        @page {
            margin: .75cm .75cm 1.25cm .75cm;
        }

        td,
        th {
            font-size: 7pt;
            line-height: 10pt;
        }

        .border-y {
            border-top: 1px double #ccc;
            border-bottom: 1px double #ccc;
        }
    </style>
@endpush

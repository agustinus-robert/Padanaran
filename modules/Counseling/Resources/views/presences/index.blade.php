@extends('counseling::layouts.default')

@section('title', 'Data presensi - ')

@section('content')
    <div class="row">
        <div class="col-md-7 col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="mdi mdi-account-multiple-outline float-left mr-2"></i>Data baru
                </div>
                <div class="card-body">
                    <form action="{{ route('counseling::presences.index') }}" method="GET">
                        <div class="mb-2 row">
                            
                            <div class="col-5">
                                <select class="form-control" name="classroom" type="text" required>
                                    <option value="">Pilih rombel</option>
                                    @foreach ($acsem->classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" @if (request('classroom') == $classroom->id) selected @endif>{{ $classroom->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-5">
                                <div class="input-group mv-2">
                                <input 
                                    type="date" 
                                    name="dateSsearch" 
                                    class="form-control" 
                                    value="{{ request('dateSsearch') ?? '' }}" 
                                />
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="input-group-append">
                                    <a class="btn btn-outline-secondary" href="{{ route('counseling::presences.index') }}"><i class="mdi mdi-refresh"></i></a>
                                    <button class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted">Menampilkan data presensi Tahun Ajaran <strong>{{ $acsem->full_name }}</strong></small>
                    </form>
                </div>
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-md-12">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('danger'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('danger') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <table class="table-bordered table-striped table-hover mb-0 table">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kelas</th>
                                <th>Guru</th>
                                <th>Tanggal</th>
                                @foreach ($presenceList as $v)
                                    <th class="text-center">{{ strtoupper(substr($v, 0, 1)) }}</th>
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $no = 1; @endphp
                        @forelse ($teacherPresences as $tp)
                            @php
                                $teacher = $tp['teacher'];
                                $teacherPresenceModel = $tp['originalPresence'] ?? null;

                                // Ambil collection dari JSON
                                $presence = $teacherPresenceModel
                                    ? collect(json_decode($teacherPresenceModel->presence, true))
                                    : collect();

                                // Fix dobel: group by session + student_id, ambil first
                                $uniquePresence = $presence->groupBy('session')
                                    ->map(fn($group) => $group->unique('student_id'))
                                    ->collapse();

                                // Hitung counts berdasarkan name setelah unique
                                $counts = $uniquePresence->countBy('name');

                                $mergedSessions = collect();

                                foreach ($teacher->schedulesTeachers as $teaching) {
                                    $dates = json_decode($teaching->dates, true) ?? [];
                                    foreach ($dates as $date => $sessions) {
                                        $temp = [];
                                        $lastKey = null;

                                        foreach ($sessions as $keySession => $session) {
                                            if (empty($session['rombel'][0]) || $session['rombel'][0] != request('classroom')) continue;

                                            $rombel = $session['rombel'][0];
                                            if (!empty($temp)
                                                && end($temp)['rombel'] === $rombel
                                                && $keySession === $lastKey + 1
                                            ) {
                                                $temp[count($temp)-1]['end'] = $session[1];
                                                $temp[count($temp)-1]['sessions'][] = $keySession + 1;
                                                $temp[count($temp)-1]['hour'][] = "{$session[0]} - {$session[1]}";
                                            } else {
                                                $temp[] = [
                                                    'date' => $date,
                                                    'start' => $session[0],
                                                    'end' => $session[1],
                                                    'sessions' => [$keySession + 1],
                                                    'hour' => ["{$session[0]} - {$session[1]}"],
                                                    'rombel' => $rombel,
                                                    'id_mapel' => $session['lesson'][0] ?? null,
                                                ];
                                            }
                                            $lastKey = $keySession;
                                        }

                                        $mergedSessions = $mergedSessions->merge($temp);
                                    }
                                }
                            @endphp

                            @foreach ($mergedSessions as $merged)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $teacher->schedulesTeachers->first()->classroom->name ?? '-' }}</td>
                                    <td>{{ $teacher->user->name }}</td>
                                    <td>
                                        <div><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($merged['date'])->isoFormat('LL') }}</div>
                                        <div><strong>Sesi {{ implode(' & ', $merged['sessions']) }}:</strong> {{ $merged['start'] }} - {{ $merged['end'] }}</div>
                                    </td>

                                    @foreach ($presenceList as $v)
                                        <td class="text-center">{{ $counts[$v] ?? '-' }}</td>
                                    @endforeach

                                    <td class="py-2 text-center" width="140" nowrap>
                                        <button
                                            class="btn btn-primary btn-sm btn-open-presence"
                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"
                                            data-dated="{{ request('dateSsearch') ?? '' }}"
                                            data-hour="{{ implode(',', $merged['hour']) }}"
                                            data-sessions='@json($merged["hour"])'
                                            data-classroom="{{ $merged['rombel'] }}"
                                            data-teacher="{{ $teacher->id }}"
                                            data-mapel="{{ $merged['id_mapel'] ?? '' }}"
                                            data-presence-id="{{ $teacherPresenceModel->id ?? '' }}"
                                            data-classroom-id="{{ request('classroom') ?? '' }}"
                                            data-action="{{ $teacherPresenceModel ? route('counseling::presences.update', ['presence' => $teacherPresenceModel->id]) : route('counseling::presence-batch') }}"
                                            data-presence='@json($uniquePresence->pluck("presence", "semester_id"))'
                                        >
                                            <i class="mdi mdi-eye"></i>
                                        </button>

                                        @if ($teacherPresenceModel)
                                            <form
                                                class="form-block form-confirm d-inline"
                                                action="{{ route('counseling::presences.destroy', ['presence' => $teacherPresenceModel->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm text-nowrap">
                                                    <i class="mdi mdi-delete"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td class="text-center" colspan="{{ count($presenceList) + 3 }}">Tidak ada data guru</td>
                        </tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-lg-4">
            @include('counseling::includes.employee-info', ['employee' => $user->employee])
            @include('account::includes.account-info')
        </div>
    </div>
@endsection

@push('scripts')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail presensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="presenceForm" method="POST">
                @csrf
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table-bordered table-striped table-hover mb-0 table">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th class="text-center">H</th>
                                    <th class="text-center">I</th>
                                    <th class="text-center">S</th>
                                    <th class="text-center">A</th>
                                </tr>
                            </thead>
                            <tbody id="modal-detail-body">
                                <tr>
                                    <td class="text-center" colspan="7">Tidak ada presensi</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@isset($currentClassroom->stsems)
<script>
$(() => {
    let stsemsFullName = @json($currentClassroom->stsems->pluck('student.full_name', 'id'));
    let stsemsNis = @json($currentClassroom->stsems->pluck('student.nis', 'id'));
    let stsemIds = @json($currentClassroom->stsems->pluck('id'));
    let stsemStudentIds = @json($currentClassroom->stsems->pluck('student_id', 'id'));
    let presencesBefore7 = @json($firstPresenceBefore7->presence ?? []);

    console.log(presencesBefore7);

    $('#exampleModal').on('show.bs.modal', (e) => {
        let button = $(e.relatedTarget);
        let presences = button.data('presence') || {};
        let action = button.data('action') || '';
        let presenceId = button.data('presence-id') || '';
        let classroomId = button.data('classroomId') || '';
        let mapelId = button.data('mapel') || '';
        let dated = button.data('dated') || '';
        let sessions = button.data('sessions') ? button.data('sessions').toString().split(',') : [];
        let teacher = button.data('teacher') || '';

        let $form = $('#presenceForm');
        $form.attr('action', action);

        $form.find('input[name="_method"]').remove();
        if (presenceId) {
            $form.append('<input type="hidden" name="_method" value="PUT">');
        }

        $form.find('input[name^="mapel"]').remove();
        if (mapelId) {
            sessions.forEach(session => {
                $form.append(`<input type="hidden" name="mapel[${mapelId}][]" value="${session}">`);
            });
        }

        let presenceLookup = {};
        presencesBefore7.forEach(p => {
            presenceLookup[p.student_id] = p.presence;
        });

        $('#modal-detail-body').html('');
        let i = 1;
        $.each(stsemIds, (idx, semesterId) => {
            let studentId = stsemStudentIds[semesterId];
            let value = presenceLookup[studentId] ?? presences[semesterId] ?? 0;

            let row = `
                <tr>
                    <td class="text-center">${i++}</td>
                    <td>${stsemsNis[semesterId] ?? '-'}</td>
                    <td>${stsemsFullName[semesterId] ?? '-'}</td>
                    <td class="text-center">
                        <input type="hidden" name="classroom_id" value="${classroomId}">
                        <input type="hidden" name="date" value="${dated}">
                        <input type="hidden" name="teacher" value="${teacher}">
                        <input type="hidden" name="presence[${semesterId}][student_id]" value="${studentId}">
                        <input type="radio" name="presence[${semesterId}][type]" value="0" ${value == 0 ? 'checked' : ''}>
                    </td>
                    <td class="text-center"><input type="radio" name="presence[${semesterId}][type]" value="1" ${value == 1 ? 'checked' : ''}></td>
                    <td class="text-center"><input type="radio" name="presence[${semesterId}][type]" value="2" ${value == 2 ? 'checked' : ''}></td>
                    <td class="text-center"><input type="radio" name="presence[${semesterId}][type]" value="3" ${value == 3 ? 'checked' : ''}></td>
                </tr>
            `;
            $('#modal-detail-body').append(row);
        });
    });
});
</script>

@endisset
@endpush

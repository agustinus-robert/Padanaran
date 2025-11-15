@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Program dan Kegiatan' : 'Program & Event'])
@section('title', request()->bahasa == 'id' ? 'Program dan Kegiatan' : 'Program & Event'.' | ')

@section('content')
    @include('web::partials.programs-section', [
        'programs' => array_slice(config('modules.web.MOCK_programs'), 0, 3),
        'lang' => $lang,
        'caption' => $caption_program_and_event,
        'category_choosen' => $arr_category,
        'program_event' => $program_event
    ])
@endsection

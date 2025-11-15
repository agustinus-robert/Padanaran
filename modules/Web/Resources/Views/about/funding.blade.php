@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Pendanaan' : 'Funding'])
@section('title', 'Pendanaan | ')

@section('content')
     @include('web::about.partials.financial-sources-section', [
        'caption' => $source_founding_section,
        'fund_1' => $fund_1,
        'fund_2' => $fund_2,
        'fund_3' => $fund_3,
        'lang' => $lang,
    ])
@endsection

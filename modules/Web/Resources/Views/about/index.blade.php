@extends('web::layouts.default2', [
    'useTransparentNavbar' => true,
])
@section('title', request()->bahasa == 'id' ? 'Beranda' : 'Tentang' . ' | ')

@section('main')

    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-0 border-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('web::home.page') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="javascript:void(0)">Pages</a></li>
                <li class="breadcrumb-item active" aria-current="page">About us</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->
    <div class="container">
        <div class="page-header page-header-big text-center" style="background-image: url('{{ asset('uploads/' . $bannerDown->location . '/' . $bannerDown->image) }}')">
            <h1 class="page-title text-white">{{ get_content_json($bannerDown)[$bahasa]['title'] }}<span class="text-white">Who We Are</span></h1>
        </div><!-- End .page-header -->
    </div><!-- End .container -->

    <div class="page-content pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-lg-0 mb-3">
                    <h2 class="title">Visi Kami</h2><!-- End .title -->
                    {!! get_content_json($vision)[$bahasa]['post0'] !!}
                </div><!-- End .col-lg-6 -->

                <div class="col-lg-6">
                    <h2 class="title">Misi Kami</h2><!-- End .title -->
                    {!! get_content_json($mission)[$bahasa]['post0'] !!}
                </div><!-- End .col-lg-6 -->
            </div><!-- End .row -->

            <div class="mb-5"></div><!-- End .mb-4 -->
        </div><!-- End .container -->

        <hr class="mb-6 mt-4">
        <h2 class="title mb-4 text-center">Tim Kami</h2>

        <div class="row">
            @foreach ($ourTeam as $value)
                <div class="col-md-4">
                    <div class="member member-anim text-center">
                        <figure class="member-media">
                            <img src="{{ asset('uploads/' . $value->location . '/' . $value->image) }}" alt="member photo">

                            <figcaption class="member-overlay">
                                <div class="member-overlay-content">
                                    <h3 class="member-title">{{ get_content_json($value)[$bahasa]['title'] }}<span></span></h3><!-- End .member-title -->
                                    <p>{{ get_content_json($value)[$bahasa]['post0'] }}</p>
                                    <div class="social-icons social-icons-simple">
                                        <a href="{{ get_content_json($value)[$bahasa]['post1'] }}" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                        <a href="{{ get_content_json($value)[$bahasa]['post2'] }}" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                        <a href="{{ get_content_json($value)[$bahasa]['post3'] }}" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                    </div><!-- End .soial-icons -->
                                </div><!-- End .member-overlay-content -->
                            </figcaption><!-- End .member-overlay -->
                        </figure><!-- End .member-media -->
                        <div class="member-content">
                            <h3 class="member-title">{{ get_content_json($value)[$bahasa]['title'] }}<span>Founder &amp; CEO</span></h3><!-- End .member-title -->
                        </div><!-- End .member-content -->
                    </div><!-- End .member -->
                </div><!-- End .col-md-4 -->
            @endforeach
        </div>
    </div><!-- End .page-content -->



@endsection

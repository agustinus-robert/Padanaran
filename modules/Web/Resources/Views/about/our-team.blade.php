@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Tim dan Management' : 'Team & Management'])
@section('title', request()->bahasa == 'id' ? 'Tim dan Management' : 'Team & Management' . ' | ')

@section('content')
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [
                ['content' => explode(' ', get_content_json($our_team)[$lang]['title'])[0], 'isBold' => true],
                [
                    'content' =>
                        (isset(explode(' ', get_content_json($our_team)[$lang]['title'])[1])
                            ? explode(' ', get_content_json($our_team)[$lang]['title'])[1]
                            : '') .
                        ' ' .
                        (isset(explode(' ', get_content_json($our_team)[$lang]['title'])[2])
                            ? explode(' ', get_content_json($our_team)[$lang]['title'])[2]
                            : ''),
                ],
            ],
             'description' => get_content_json($our_team)[$lang]['post0'],
        ])
    </section>

    <section class="container mx-auto md:px-16 py-16">
        <div class="grid grid-cols-2 gap-x-4 md:gap-x-10 gap-y-10 place-items-center w-fit mx-auto">
            <a href="{{ url(request()->bahasa . '/our-team') }}#pembina"
                class="hover:shadow-2xl group transition-shadow bg-muted rounded-lg py-12 space-y-1 relative w-full md:w-96 flex items-center justify-center col-span-2">
                <h5 class="font-medium uppercase group-hover:translate-x-2 transition-transform">{{(request()->bahasa == 'id' ? 'Pembina' : 'Governing Board')}} 
                </h5>
                <div class="bg-muted-foreground absolute h-5 w-0.5 -bottom-5 left-1/2 -translate-x-1/2"></div>
                <div
                    class="bg-muted-foreground absolute h-0.5 -bottom-5 w-[53%] md:w-[calc(100%+2.5rem)] left-1/2 -translate-x-1/2">
                </div>
            </a>
            <a href="{{ url(request()->bahasa . '/our-team') }}#pengurus"
                class="hover:shadow-2xl group transition-shadow bg-muted rounded-lg py-12 space-y-1 relative w-full md:w-96 flex items-center justify-center">
                <div class="bg-muted-foreground absolute h-5 w-0.5 -top-5 left-1/2 -translate-x-1/2"></div>
                <h5 class="font-medium uppercase group-hover:translate-x-2 transition-transform">{{(request()->bahasa == 'id' ? 'Pengurus' : 'Supervisory Board')}}</h5>
            </a>
            <a href="{{ url(request()->bahasa . '/our-team') }}#pengawas"
                class="hover:shadow-2xl group transition-shadow bg-muted rounded-lg py-12 space-y-1 relative w-full md:w-96 flex items-center justify-center">
                <div class="bg-muted-foreground absolute h-5 w-0.5 -top-5 left-1/2 -translate-x-1/2"></div>
                <h5 class="font-medium uppercase group-hover:translate-x-2 transition-transform">{{(request()->bahasa == 'id' ? 'Pengawas' : 'Executive')}}</h5>
            </a>
        </div>
    </section>

    @include('web::partials.our-team-section', [
        'id' => 'pembina',
        'members' => array_slice(config('modules.web.MOCK_team_members'), 0, 4),
        'our_team' => (request()->bahasa == 'id' ? 'Pembina' : 'Governing Board'),
        'our_team_content' => $our_team_pembina,
        'lang' => $lang,
    ])
      @include('web::partials.our-team-section', [
        'id' => 'pengawas',
        'members' => array_slice(config('modules.web.MOCK_team_members'), 0, 4),
        'our_team' => (request()->bahasa == 'id' ? 'Pengawas' : 'Executive'),
        'our_team_content' => $our_team_pengawas,
        'lang' => $lang,
    ])
    @include('web::partials.our-team-section', [
        'id' => 'pengurus',
        'members' => array_slice(config('modules.web.MOCK_team_members'), 0, 4),
        'our_team' => (request()->bahasa == 'id' ? 'Pengurus' : 'Supervisory Board'),
        'our_team_content' => $our_team_pengurus,
        'lang' => $lang,
    ])
@endsection


@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', (carouselData = {
                slides: [],
                intervalTime: 0,
            }, ) => ({
                slides: carouselData.slides,
                autoplayIntervalTime: carouselData.intervalTime,
                currentSlideIndex: 1,
                isPaused: false,
                autoplayInterval: null,
                resetInterval() {
                    clearInterval(this.autoplayInterval);
                    this.autoplay();
                },
                previous() {
                    this.resetInterval();
                    if (this.currentSlideIndex > 1) {
                        this.currentSlideIndex = this.currentSlideIndex - 1
                        return
                    }
                    this.currentSlideIndex = this.slides.length
                },
                next() {
                    this.resetInterval();
                    if (this.currentSlideIndex < this.slides.length) {
                        this.currentSlideIndex = this.currentSlideIndex + 1
                        return
                    }
                    this.currentSlideIndex = 1
                },
                autoplay() {
                    console.log(this.slides)
                    this.autoplayInterval = setInterval(() => {
                        !this.isPaused && this.next()
                    }, this.autoplayIntervalTime)
                },
                setAutoplayIntervalTime(newIntervalTime) {
                    clearInterval(this.autoplayInterval)
                    this.autoplayIntervalTime = newIntervalTime
                    this.autoplay()
                },
            }))
        })
    </script>
@endpush

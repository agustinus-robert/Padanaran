@extends('web::layouts.about', ['headerTitle' => request()->bahasa == 'id' ? 'Target & Sasaran' : 'Target & Objective'])
@section('title', request()->bahasa == 'id' ? 'Target & Sasaran' : 'Target & Objective'.' | ')

@php
    $targets = [
        'Voluptate qui id enim officia',
        'Nisi magna anim in qui dolore cupidatat reprehenderit.',
        'Ad commodo sint velit non reprehenderit in ad ipsum eu nisi.',
        'Id incididunt id eu sint culpa.',
        'Tempor excepteur cupidatat culpa dolor.',
        'Nostrud do aliqua pariatur reprehenderit.',
        'Officia elit velit velit ipsum ex dolor.',
        'Veniam pariatur magna proident eu est in proident dolor elit fugiat Lorem.',
    ];
    $objectives = [
        'Incididunt nulla do exercitation ipsum incididunt excepteur qui laborum dolore enim velit.',
        'Voluptate aliquip cupidatat sunt commodo dolore consectetur veniam pariatur sit dolor consequat tempor aliqua exercitation.',
        'Occaecat duis sint duis duis elit elit sint non elit sint quis deserunt ex.',
        'Excepteur aliquip quis eiusmod ea laboris consequat cupidatat aute fugiat pariatur culpa officia nostrud nisi.',
        'Nisi dolor dolor ullamco occaecat esse duis nostrud.',
        'Deserunt qui irure consectetur tempor labore veniam et labore reprehenderit labore culpa elit deserunt occaecat.',
        'Voluptate duis tempor magna et ullamco esse est magna.',
    ];
@endphp

@section('content')
    <section class="relative mb-12 md:mb-0">
        <div
            class="bg-primary w-[130vw] h-[140vh] md:h-[760px] absolute top-0 -z-20 rounded-b-full left-1/2 -translate-x-1/2">
        </div>
        <div class="container mx-auto md:px-16 py-16 space-y-8">
            @include('web::partials.section-header', [
                'title' => [['content' => 'Target']],
                'description' =>
                    'Fugiat ullamco quis adipisicing aliquip laboris quis eiusmod labore incididunt duis',
                'isDarkBackground' => true,
            ])
            <div class="grid md:grid-cols-2 place-items-center gap-y-4">
                <div x-data="carousel({
                    intervalTime: 6000,
                    slides: [{
                            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-1.webp',
                            imgAlt: 'Vibrant abstract painting with swirling blue and light pink hues on a canvas.',
                            title: 'Dolor sint culpa cillum ut',
                            description: 'Dolor ipsum id esse id magna non Dolore minim qui laborum ullamco ipsum adipisicing aute culpa nisi minim consectetur cupidatat.'
                        },
                        {
                            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-2.webp',
                            imgAlt: 'Vibrant abstract painting with swirling red, yellow, and pink hues on a canvas.',
                            title: 'Lorem do irure esse pariatur proident',
                            description: 'In proident tempor dolor voluptate In nostrud ad quis dolor ipsum labore mollit eiusmod non exercitation et et mollit labore.'
                        },
                        {
                            imgSrc: 'https://penguinui.s3.amazonaws.com/component-assets/carousel/default-slide-3.webp',
                            imgAlt: 'Vibrant abstract painting with swirling blue and purple hues on a canvas.',
                            title: 'Tempor commodo elit commodo et',
                            description: 'Cupidatat occaecat quis ut esse tempor dolore Mollit laborum pariatur dolor deserunt occaecat officia anim proident ipsum.'
                        },
                    ],
                })" x-init="autoplay" class="relative w-full overflow-hidden">
                    <div class="relative min-h-[60svh] w-full">
                        <template x-for="(slide, index) in slides">
                            <div x-cloak x-show="currentSlideIndex == index + 1"
                                class="absolute inset-0 flex items-center justify-center"
                                x-transition.opacity.duration.1000ms>
                                <div class="rounded-lg bg-background max-w-80 pb-4 space-y-4 shadow-xl">
                                    <img src="/images/rand-1.jpeg" alt="gallery-item-1" class="rounded-t-lg w-full h-52" />
                                    <div class="px-4 space-y-4">
                                        <h4 class="font-bold line-clamp-2 text-center" x-text="slide.title">
                                        </h4>
                                        <p class="text-center text-sm" x-text="slide.description">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="absolute rounded-md bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2 px-1.5 py-1 md:px-2 items-center"
                        role="group" aria-label="slides">
                        <template x-for="(slide, index) in slides">
                            <button class="cursor-pointer rounded-full transition"
                                x-on:click="(currentSlideIndex = index + 1), setAutoplayIntervalTime(autoplayIntervalTime)"
                                x-bind:class="[currentSlideIndex === index + 1 ? 'bg-muted dark:bg-foreground size-2.5' :
                                    'bg-muted/40 dark:bg-foreground/40 size-2'
                                ]"
                                x-bind:aria-label="'slide ' + (index + 1)"></button>
                        </template>
                    </div>
                </div>
                <ul class="space-y-2">
                    @foreach ($targets as $target)
                        <li class="flex items-center gap-2 text-primary-foreground">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5 text-primary-300"
                                fill="currentColor" stroke="currentColor">
                                <path
                                    d="M0.41,13.41L6,19L7.41,17.58L1.83,12M22.24,5.58L11.66,16.17L7.5,12L6.07,13.41L11.66,19L23.66,7M18,7L16.59,5.58L10.24,11.93L11.66,13.34L18,7Z" />
                            </svg>
                            {{ $target }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        @include('web::partials.section-header', [
            'title' => [['content' => 'SASARAN', 'isBold' => true]],
            'description' =>
                'Ex minim excepteur labore non excepteur aute sint sunt anim laboris consequat excepteur.',
        ])
        <ul class="flex flex-col gap-2 items-center justify-center">
            @foreach ($objectives as $objective)
                <li class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="size-5 text-primary dark:text-primary-500" fill="currentColor" stroke="currentColor">
                        <path
                            d="M0.41,13.41L6,19L7.41,17.58L1.83,12M22.24,5.58L11.66,16.17L7.5,12L6.07,13.41L11.66,19L23.66,7M18,7L16.59,5.58L10.24,11.93L11.66,13.34L18,7Z" />
                    </svg>
                    {{ $objective }}
                </li>
            @endforeach
        </ul>
    </section>
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

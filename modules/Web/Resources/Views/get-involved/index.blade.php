@extends('web::layouts.default')
@section('title', 'Bergabung | ')

@section('main')
    <header class="container mx-auto md:px-16 py-16">
        <div class="relative md:mb-0 mb-8">
            <div
                class="absolute -left-6 md:-left-20 h-[1px] w-4 md:w-16 bg-primary dark:bg-primary-500 top-1/2 translate-y-1/2">
            </div>
            <span class="text-primary-text font-bold text-xs">{{ get_content_json($caption)[$lang]['title'] }}</span>
        </div>
        <div class="grid md:grid-cols-2 place-items-center gap-4 md:gap-16">
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-bold">{{ get_content_json($caption)[$lang]['post0'] }}
                </h1>
                {!! get_content_json($caption)[$lang]['post1'] !!}
            </div>
            <img src="{{ get_image($caption->location, $caption->image) }}" alt="Get involved"
                class="object-cover w-full h-full rounded-lg">
        </div>
    </header>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        <h3 class="text-2xl font-bold text-center">
            {{ $lang == 'id' ? 'Bagaimana Anda Ingin Berkontribusi?' : 'How would you like to contribute?' }}
        </h3>
        <div class="grid grid-cols-3 gap-2 md:gap-10 md:flex md:items-center md:justify-center">
            <a href="{{ route('web::get-involved.donation') }}">
                <button
                    class="flex flex-col items-center justify-center gap-2 md:gap-4 p-4 bg-teal-600 dark:bg-teal-700 hover:bg-teal-600/90 hover:dark:bg-teal-700/90 rounded-lg md:h-52 md:w-52">
                    <img src="/vectors/donate.svg" alt="Heart Icon" class="w-10 h-10 md:w-20 md:h-20">
                    <h3 class="md:text-xl font-bold text-white">{{ $lang == 'id' ? 'Donasi' : 'Donation' }}</h3>
                </button>
            </a>
            <button
                class="flex flex-col items-center justify-center gap-2 md:gap-4 p-4 bg-lime-600 dark:bg-lime-700 hover:bg-lime-600/90 hover:dark:bg-lime-700/90 rounded-lg md:h-52 md:w-52">
                <img src="/vectors/team-work.svg" alt="Clipboard Icon" class="w-10 h-10 md:w-20 md:h-20">
                <h3 class="md:text-xl font-bold text-white">{{ $lang == 'id' ? 'Sukarelawan' : 'Volunteer' }}</h3>
            </button>
            <button
                class="flex flex-col items-center justify-center gap-2 md:gap-4 p-4 bg-primary-600 dark:bg-primary-700 hover:bg-primary-600/90 hover:dark:bg-primary-700/90 rounded-lg md:h-52 md:w-52">
                <img src="/vectors/hand-shake.svg" alt="Hand Icon" class="w-10 h-10 md:w-20 md:h-20">
                <h3 class="md:text-xl font-bold text-white">{{ $lang == 'id' ? 'Menjadi Partner' : 'Partnership' }}</h3>
            </button>
            <button
                class="flex flex-col items-center justify-center gap-2 md:gap-4 p-4 bg-blue-600 dark:bg-blue-700 hover:bg-blue-600/90 hover:dark:bg-blue-700/90 rounded-lg md:h-52 md:w-52">
                <img src="/vectors/briefcase.svg" alt="Hand Icon" class="w-10 h-10 md:w-20 md:h-20">
                <h3 class="md:text-xl font-bold text-white">{{ $lang == 'id' ? 'Karier' : 'Career' }}</h3>
            </button>
        </div>
    </section>
    <section class="py-16">
        @foreach ($content as $key => $val)
            <div class="py-8 {{ $key == 1 || $key == 3 ? 'bg-muted' : '' }}">
                <div
                    class="container mx-auto md:px-16 grid md:grid-cols-3 {{ $key == 1 || $key == 3 ? 'flex-row-reverse' : '' }}">
                    <div class="md:col-span-2 md:py-0 py-8 md:px-16 justify-center flex items-center gap-2 flex-col">
                        <h4 class="text-2xl font-bold text-center">{{ get_content_json($val)[$lang]['title'] }}</h4>
                        {!! get_content_json($val)[$lang]['post0'] !!}
                    </div>
                    <img src="{{ get_image($val->location, $val->image) }}" alt="Our Works"
                        class="object-cover w-full h-full rounded-lg {{ $key == 1 || $key == 3 ? 'md:order-first' : '' }}">
                </div>
            </div>
        @endforeach
        {{-- <div class="grid md:grid-cols-3 flex-row-reverse">
            <div class="md:col-span-2 md:py-0 py-8 md:px-16 justify-center flex items-center gap-2 flex-col">
                <h4 class="text-2xl font-bold text-center text-white">Donasi</h4>
                <p>
                    Donasi yang Anda lakukan, baik besar maupun kecil, akan menciptakan perbedaan yang begitu besar.
                </p>
            </div>
            <img src="/images/rand-2.jpg" alt="Our Works" class="object-cover w-full h-full rounded-lg md:order-first">
        </div>
        <div class="grid md:grid-cols-3">
            <div class="md:col-span-2 md:py-0 py-8 md:px-16 justify-center flex items-center gap-2 flex-col">
                <h4 class="text-2xl font-bold text-center text-white">Menjadi partner kami</h4>
                <p>
                    Perkenalkan diri Anda dan mari bicarakan bagaimana Anda akan menjadi partner yang hebat.
                </p>
            </div>
            <img src="/images/rand-1.jpeg" alt="Our Works" class="object-cover w-full h-full rounded-lg">
        </div>
        <div class="grid md:grid-cols-3 flex-row-reverse">
            <div class="md:col-span-2 md:py-0 py-8 md:px-16 justify-center flex items-center gap-2 flex-col">
                <h4 class="text-2xl font-bold text-center text-white">Karir</h4>
                <p>
                    Reprehenderit incididunt sunt est fugiat laborum anim deserunt. Quis Lorem laborum Lorem occaecat
                    cupidatat non cillum nisi aute. Id eiusmod dolor anim deserunt voluptate commodo mollit. Laboris aliqua
                    deserunt officia aute velit id eiusmod.
                </p>
            </div>
            <img src="/images/rand-2.jpg" alt="Our Works" class="object-cover w-full h-full rounded-lg md:order-first">
        </div> --}}
    </section>
    <section class="py-16 space-y-8">
        @include('web::partials.partners-section')
        <p class="text-xs text-muted-foreground text-center">
            {{ $lang == 'id' ? 'Dipercayai oleh banyak perusahaan dan organisasi' : 'Trusted by many companies and organizations.' }}
        </p>
    </section>
    @include('web::partials.contact-us-section')
    @include('web::partials.map-section')
@endsection

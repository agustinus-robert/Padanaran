@extends('web::layouts.default')
@section('title', 'Karir | ')

@php($data = [[], [], [], []])

@section('main')
    <header class="flex flex-col gap-4 items-center justify-center py-32 bg-center"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('/images/rand-4.jpg');">
        <div class="flex items-center justify-center flex-col gap-1">
            <h1 class="text-2xl md:text-4xl font-bold text-center text-white">
                {{(request()->bahasa == 'id' ? 'Temukan' : 'Find')}}
                <span class="text-primary-400">
                    {{request()->bahasa == 'id' ? 'Karir Impianmu' : 'Your Dream Career'}}</span>{{request()->bahasa == 'id' ? 'Bersama Kami Di Sini!' : 'Us Starts Here!'}} 
            </h1>

            <form id="searchFormCareer" action="#" method="GET" class="relative md:w-1/2 w-full">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" aria-hidden="true"
                    class="text-muted-foreground absolute left-5 top-1/2 size-5 -translate-y-1/2"
                    style="pointer-events: none;">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="search" id="searchInputCareer" name="search" placeholder="{{request()->bahasa == 'id' ? 'Cari lowongan disini...' : 'Search for job vacancies here...'}}
"
                    aria-label="search" value="{{ request()->get('search') }}"
                    class="w-full rounded-full py-3 pl-12 pr-4 text-sm focus-visible:outline focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 text-black" />
            </form>
        </div>
    </header>
    <section class="container mx-auto md:px-16 py-16">
        <div class="grid md:grid-cols-2 gap-4" id="resultContainer">
            @foreach ($career_content as $key => $val)
                <a
                    href="{{ route('web::get_involved.career.show', ['bahasa' => $lang, 'slug' => get_content_json($val)[$lang]['slug']]) }}">
                    <div
                        class="shadow hover:shadow-md transition-shadow w-full flex flex-col sm:flex-row gap-3 sm:items-center  justify-between px-5 py-4 rounded-lg border border-border">
                        <div>
                            <span
                                class="text-primary-text text-sm">{{ category_by_post_id($val->id, '1812836995295365')->title }}</span>
                            <h3 class="font-bold mt-px">{{ get_content_json($val)[$lang]['title'] }}</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <x-web::badge>{{ category_by_post_id($val->id, '1812836908171625')->title }}</x-web::badge>
                                <span class="text-muted-foreground text-sm flex gap-1 items-center"> <svg
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4"
                                        fill="currentColor">
                                        <path
                                            d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z" />
                                    </svg> {{ get_content_json($val)[$lang]['post3'] }}</span>
                            </div>
                        </div>
                        <x-web::button
                            x-on:click="event.preventDefault(); window.location.href='{{ route('web::get_involved.career.show', ['bahasa' => $lang, 'slug' => get_content_json($val)[$lang]['slug']]) }}#apply'">
                            Lamar
                        </x-web::button>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
         // Ambil elemen form
        const searchFormCareer = document.getElementById('searchFormCareer');
        const searchInputCareer = document.getElementById('searchInputCareer');

        // Tangani submit form secara eksplisit
        searchFormCareer.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah form dari submit default
            performSearch(); // Panggil fungsi pencarian AJAX
        });

        // Tangani tombol Enter secara eksplisit dalam input
        searchInputCareer.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Cegah form dari submit saat Enter ditekan
                performSearch(); // Panggil fungsi pencarian AJAX
            }
        });

    function performSearch() {
        let query = searchInputCareer.value;
        let url = `{{ route('web::load.search', ['bahasa' => $lang]) }}?search=${encodeURIComponent(query)}`;
        const rootUrl = "{{ url('/') }}";

        fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            const resultContainer = document.getElementById('resultContainer');
            resultContainer.innerHTML = ''; // Kosongkan hasil pencarian sebelumnya

            // Loop melalui hasil data dan buat elemen HTML dinamis
            data.data.forEach(post => {
                const langx = "{{ $lang }}";
                const json_data = JSON.parse(post.content).{{ $lang }};
                const postSlug = json_data.slug; 
                const postTitle = json_data.title; 
                const categoryTitle = data.category[post.id] ?? 'Uncategory'; 
                const location = json_data.post3;

               const postElement = `
                    <a href="${rootUrl}/${langx}/get-involved/career/${postSlug}">
                        <div class="shadow hover:shadow-md transition-shadow w-full flex flex-col sm:flex-row gap-3 sm:items-center justify-between px-5 py-4 rounded-lg border border-border">
                            <div>
                                <span class="text-primary-text text-sm">${categoryTitle}</span>
                                <h3 class="font-bold mt-px">${postTitle}</h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <x-web::badge>${categoryTitle}</x-web::badge>
                                    <span class="text-muted-foreground text-sm flex gap-1 items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" fill="currentColor">
                                            <path d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z" />
                                        </svg> ${location}
                                    </span>
                                </div>
                            </div>
                            <a href="/get-involved/career/${postSlug}#apply">
                                <x-web::button>
                                    Lamar
                                </x-web::button>
                            </a>
                        </div>
                    </a>
                `;
                // Masukkan elemen post ke dalam container
                resultContainer.innerHTML += postElement;
            });
        })
        .catch(error => console.error('Error:', error));
    }
});
    </script>
@endsection

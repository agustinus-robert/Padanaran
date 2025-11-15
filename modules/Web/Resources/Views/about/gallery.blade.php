@extends('web::layouts.about')
@section('title', 'Galeri | ')

@section('content')
    <header class="container mx-auto md:px-16 py-16 grid gap-4" x-data="{ highlightedImage: 0, images: {{$galeri}} }">
        <div>
            <img class="h-auto max-w-full rounded-lg" x-bind:src="`{{get_image('${image.location}', '${image.image}')}}`"
                x-bind:alt="images[highlightedImage].title" />
        </div>
        <div class="grid grid-cols-5 gap-4">
            <template x-for="(image, index) in images" :key="index">
                <div x-on:click="highlightedImage = index">
                    <img class="h-auto max-w-full rounded-lg hover:scale-105 transition-transform cursor-pointer"
                        x-bind:src="`{{get_image('${image.location}', '${image.image}')}}`" x-bind:alt="image.title">
                </div>
            </template>
        </div>
    </header>
    <div class="flex items-center justify-center gap-8 container mx-auto md:px-16">
        <hr class="border border-border flex-1" />
        <div class="flex items-center justify-center gap-1">
            <form class="relative min-w-full md:min-w-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" aria-hidden="true"
                    class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-muted-foreground">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="search" name="search" placeholder="Cari gambar disini..." aria-label="search"
                    class="rounded-md border w-full border-border py-2.5 pl-10 pr-2 text-sm bg-background focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:focus-visible:outline-white" />
            </form>
            <div x-data="{ isOpen: false, openedWithKeyboard: false }" class="relative" @keydown.esc.window="isOpen = false, openedWithKeyboard = false">
                <x-web::button variant="ghost" size="icon" class="text-muted-foreground" @click="isOpen = ! isOpen">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                    </svg>
                </x-web::button>
                <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard"
                    @click.outside="isOpen = false, openedWithKeyboard = false" @keydown.down.prevent="$focus.wrap().next()"
                    @keydown.up.prevent="$focus.wrap().previous()"
                    class="absolute top-11 right-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-border bg-background py-1.5 z-50"
                    role="menu">
                    <a href="#" class="px-4 py-2 hover:bg-muted text-sm">
                        Tanggal Rilis
                    </a>
                    <a href="#" class="px-4 py-2 hover:bg-muted text-sm">
                        Alfabetis
                    </a>
                </div>
            </div>
        </div>
    </div>
    <section class="container mx-auto md:px-16 py-16 grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach (config('modules.web.MOCK_galleries') as $gallery)
            <div class="relative group">
                <img src="{{ $gallery['imageUrl'] }}" alt="gallery-item-1"
                    class="rounded-lg object-contain w-full group-hover:brightness-50 transition-all duration-500" />
                <div class="absolute w-full p-4 opacity-0 -bottom-10 group-hover:bottom-0 group-hover:opacity-100 duration-500 transition-all"
                    id="overlay">
                    <h5 class="text-sm text-white font-medium">
                        {{ $gallery['title'] }}
                    </h5>
                    <p class="text-xs text-white">
                        {{ $gallery['description'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </section>
@endsection

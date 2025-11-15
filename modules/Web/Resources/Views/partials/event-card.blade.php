@props(['date', 'month', 'title', 'class' => ''])

<article
    class="bg-primary text-white rounded-lg p-6 flex items-center justify-between gap-12 cursor-pointer hover:bg-primary/90 transition-colors group {{ $class }}">
    <div class="flex items-center gap-4">
        <div class="flex items-center justify-center flex-col">
            <h4 class="text-3xl leading-7 font-medium">{{ $date }}</h4>
            <span class="text-xs">{{ $month }}</span>
        </div>
        <div>
            <div class="flex items-center gap-4">
                <span class="text-xs"> NEXT EVENTS </span>
                <hr class="w-10" />
            </div>
            <span class="font-bold text-xl">{{ $title }}</span>
        </div>
    </div>
    <button class="rounded-full p-2 bg-white text-black">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="size-5 group-hover:translate-x-0.5 transition-transform">
            <path d="M5 12h14" />
            <path d="m12 5 7 7-7 7" />
        </svg>
    </button>
</article>

         {{-- <div class="absolute container mx-auto md:px-16 md:-top-[45%] -top-[13%] left-1/2 -translate-x-1/2">
            <div x-data="{ playing: false }" class="relative w-full md:h-[520px] h-full aspect-video">
                <video x-ref="video" :controls="playing" @play="playing = true"
                    class="w-full h-full max-w-full rounded-lg">
                    <source src="/videos/rand-1.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <img x-show="!playing" src="/images/rand-2.jpg" alt="about"
                    class="absolute inset-0 w-full h-full object-cover rounded-lg brightness-50">
                <div x-show="!playing" class="absolute inset-0 flex items-center justify-center w-full h-full">
                    <button @click="$refs.video.play()" class="focus:outline-none bg-white rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5 text-black md:size-8"
                            fill="currentColor" stroke="currentColor">
                            <path d="M8,5.14V19.14L19,12.14L8,5.14Z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div> --}}
         <div class="space-y-6">
             <span class=" font-bold text-sm"></span>
             <h3 class="text-xl font-bold ">{{ get_content_json($vision)[$lang]['title'] }}</h3>
             <p class="text-muted-foreground text-sm">
                 {!! preg_replace('/<span[^>]+\>/i', '', get_content_json($vision)[$lang]['post0']) !!}
             </p>
         </div>
         <div class="space-y-6">
             <span class=" font-bold text-sm"></span>
             <h3 class="text-xl font-bold ">{{ get_content_json($mission)[$lang]['title'] }}</h3>
             <p class="text-muted-foreground text-sm">
                 {!! preg_replace('/<span[^>]+\>/i', '', get_content_json($mission)[$lang]['post0']) !!}
             </p>
         </div>

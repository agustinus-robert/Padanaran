<section class="relative">
    <div class="bg-primary w-full absolute top-0 h-80 -z-20">
    </div>
    <div class="container mx-auto md:px-16 py-16 space-y-16">
        @include('web::partials.section-header', [
            'title' => [
                ['content' => request()->bahasa == 'id' ? 'KONTAK' : 'Contact', 'isBold' => true],
                ['content' => request()->bahasa == 'id' ? 'KAMI' : 'US']
            ],
            'description' => 'Ipsum ut nostrud consequat veniam Ut voluptate amet ipsum magna dolore et occaecat',
            'isDarkBackground' => true,
        ])
        <div class="grid md:grid-cols-3 gap-16 md:gap-4">
            <div
                class="px-4 pb-4 pt-14 rounded-lg relative flex items-center justify-center flex-col gap-6 shadow-md border border-border bg-background">
                <div
                    class="p-6 text-muted-foreground rounded-full shadow-md border border-border absolute -top-10 bg-background left-1/2 -translate-x-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                            clip-rule="evenodd" />
                    </svg>

                </div>
                <h4 class="text-lg text-center font-semibold">
                    {{request()->bahasa == 'id' ? 'Hubungi Kami' : 'Contact US'}}
                </h4>
                <p class="text-center font-semibold">+62-274-889320</p>
                <x-web::button variant="link" class="flex gap-2">Hubungi Kami
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </x-web::button>
            </div>
            <div
                class="px-4 pb-4 pt-14 rounded-lg relative flex items-center justify-center flex-col gap-6 shadow-md border border-border bg-background">
                <div
                    class="p-6 text-muted-foreground rounded-full shadow-md border border-border absolute -top-10 bg-background left-1/2 -translate-x-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h4 class="text-lg text-center font-semibold">
                    {{request()->bahasa == 'id' ? 'Lokasi Kantor' : 'Location'}}
                </h4>
                <p class="text-center font-semibold">Jl. Damai, Mudal RT 01 RW 19, Sariharjo, Ngaglik, Sleman, Yogyakarta
                </p>
                <x-web::button variant="link" class="flex gap-2">{{request()->bahasa == 'id' ? 'Datangi Kami' : 'Come Us'}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </x-web::button>
            </div>
            <div
                class="px-4 pb-4 pt-14 rounded-lg relative flex items-center justify-center flex-col gap-6 shadow-md border border-border bg-background">
                <div
                    class="p-6 text-muted-foreground rounded-full shadow-md border border-border absolute -top-10 bg-background left-1/2 -translate-x-1/2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path
                            d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                        <path
                            d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                    </svg>
                </div>
                <h4 class="text-lg text-center font-semibold">
                    Email
                </h4>
                <p class="text-center font-semibold">
                    sekretarispengurusysby@suarabhakti.id
                </p>
                <x-web::button variant="link" class="flex gap-2">{{request()->bahasa == 'id' ? 'Email Kami' : 'Email Us'}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd"
                            d="M16.72 7.72a.75.75 0 0 1 1.06 0l3.75 3.75a.75.75 0 0 1 0 1.06l-3.75 3.75a.75.75 0 1 1-1.06-1.06l2.47-2.47H3a.75.75 0 0 1 0-1.5h16.19l-2.47-2.47a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </x-web::button>
            </div>
        </div>
    </div>
</section>

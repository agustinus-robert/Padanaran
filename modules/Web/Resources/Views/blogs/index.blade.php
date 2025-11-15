@extends('web::layouts.default')
@section('title', 'Blog | ')

@section('main')
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="flex items-center gap-4">
            <h3 class="text-3xl font-bold">{{ $lang == 'id' ? 'ARTIKEL TERBARU' : 'NEW ARTICLE' }}</h3>
            <hr class="border border-border w-20" />
        </div>
        <div class="grid md:grid-cols-[60%,40%] gap-8 md:gap-16">
            <div class="md:grid grid-cols-2 rounded-lg">
                <img class="rounded-lg col-span-2 w-full aspect-video"
                    src="{{ get_image($blog_last_content->location, $blog_last_content->image) }}" alt="Blog Image">
                <div class="py-4 md:px-4 px-0 flex justify-between flex-col">
                    <h2 class="font-bold text-3xl">
                        {{ get_content_json($blog_last_content)[$lang]['title'] }}</h2>
                    <div class="flex items-center gap-4">
                        <x-web::badge>
                            {{ !empty(category_by_post_id($blog_last_content->id)->title) ? category_by_post_id($blog_last_content->id)->title : 'Uncategorize' }}
                        </x-web::badge>
                        <span class="text-muted-foreground text-xs">
                            {{ tgl_indo(date('Y-m-d', strtotime($blog_last_content->created_at))) }} </span>
                    </div>
                </div>
                <p class="text-sm text-muted-foreground line-clamp-3 py-4 md:px-4 px-0">
                    {!! get_content_json($blog_last_content)[$lang]['post0'] !!}</p>
            </div>
            <div class="bg-primary p-10 rounded-lg h-fit space-y-8">
                <h4 class="font-bold text-4xl text-primary-foreground">
                    {{ $lang == 'id'
                        ? 'Ikuti Kami di Sosial Media Agar Selalu
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Update'
                        : 'Follow us on social Network, to see our update' }}
                </h4>
                <div class="flex items-center justify-center gap-10">
                    <a href="#" target="_blank" class="block">
                        <button
                            class="border-2 border-primary-foreground text-primary-foreground rounded-full p-2.5 hover:bg-primary-foreground hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                        </button>
                    </a>
                    <a href="#" target="_blank" class="block">
                        <button
                            class="border-2 border-primary-foreground text-primary-foreground rounded-full p-2.5 hover:bg-primary-foreground hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                            </svg>
                        </button>
                    </a> <a href="#" target="_blank" class="block">
                        <button
                            class="border-2 border-primary-foreground text-primary-foreground rounded-full p-2.5 hover:bg-primary-foreground hover:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10">
                                <path
                                    d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z" />
                            </svg>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center gap-8">
            <hr class="border border-border flex-1" />
            <div class="flex items-center justify-center gap-1">
                <form class="relative min-w-full md:min-w-80">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" aria-hidden="true"
                        class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-muted-foreground">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="search" id="search-input" name="search" value="{{isset($_GET['search']) ? str_replace('#', '', $_GET['search']) : ''}}" placeholder="Cari blog disini..."
                        aria-label="search"
                        class="rounded-md border w-full border-border py-2.5 pl-10 pr-2 text-sm bg-background focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:focus-visible:outline-white" />
                </form>
                <div x-data="{ isOpen: false, openedWithKeyboard: false }" class="relative"
                    @keydown.esc.window="isOpen = false, openedWithKeyboard = false">
                    <x-web::button variant="ghost" size="icon" class="text-muted-foreground" @click="isOpen = ! isOpen"
                        title="Filter berdasarkan">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="currentColor"
                            stroke="currentColor">
                            <path d="M6,13H18V11H6M3,6V8H21V6M10,18H14V16H10V18Z" />
                        </svg>
                    </x-web::button>
                    <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard"
                        @click.outside="isOpen = false, openedWithKeyboard = false"
                        @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                        class="absolute top-11 right-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-border bg-background py-1.5 z-50"
                        role="menu">

                        <span class="text-xs text-muted-foreground px-4 py-1 font-medium">Kategori</span>
                        @if (count($kategori) > 0)
                            @foreach ($kategori as $key => $value)
                                <label for="{{ $value->title }}"
                                    class="inline-flex cursor-pointer hover:bg-muted items-center justify-between gap-3 px-4 py-2 text-sm [&:has(input:checked)]:text-gray-900 dark:[&:has(input:checked)]:text-white [&:has(input:disabled)]:opacity-75 [&:has(input:disabled)]:cursor-not-allowed">
                                    <span>{{ $value->title }}</span>
                                    <div class="relative flex items-center">
                                        <input id="events" type="checkbox" value="{{ $value->id }}"
                                            class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-border bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-gray-800 checked:focus:outline-black active:outline-offset-0 disabled:cursor-not-allowed dark:border-gray-700 dark:bg-gray-950 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-gray-300 dark:checked:focus:outline-white" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                                            stroke="currentColor" fill="none" stroke-width="4"
                                            class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-white peer-checked:visible dark:text-black">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div x-data="{ isOpen: false, openedWithKeyboard: false }" class="relative"
                    @keydown.esc.window="isOpen = false, openedWithKeyboard = false">
                    <x-web::button variant="ghost" size="icon" class="text-muted-foreground"
                        title="Urutkan berdasarkan" @click="isOpen = ! isOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 7.5 7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                    </x-web::button>
                    <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard"
                        @click.outside="isOpen = false, openedWithKeyboard = false"
                        @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                        class="absolute top-11 right-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-md border border-border bg-background py-1.5 z-50"
                        role="menu">
                        <a href="javascript:void(0)" id="tgl" class="px-4 py-2 hover:bg-muted text-sm">
                            Tanggal Rilis
                        </a>
                        <a href="javascript:void(0)" id="order-asc" class="px-4 py-2 hover:bg-muted text-sm">
                            Alfabetis
                        </a>
                    </div>
                </div>
                @if (request()->view == 'list')
                    <a href="{{ url(request()->bahasa . '/blogs') }}#item-list">
                        <x-web::button variant="ghost" size="icon" class="text-muted-foreground"
                            title="Lihat sebagai kartu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5"
                                fill="currentColor">
                                <path
                                    d="M3 11H11V3H3M5 5H9V9H5M13 21H21V13H13M15 15H19V19H15M3 21H11V13H3M5 15H9V19H5M13 3V11H21V3M19 9H15V5H19Z" />

                            </svg>
                        </x-web::button>
                    </a>
                @else
                    <a href="{{ url(request()->bahasa . '/blogs') . '?view=list#item-list' }}">
                        <x-web::button variant="ghost" size="icon" class="text-muted-foreground"
                            title="Lihat sebagai list judul">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5"
                                fill="currentColor">
                                <path
                                    d="M3 5V19H20V5H3M7 7V9H5V7H7M5 13V11H7V13H5M5 15H7V17H5V15M18 17H9V15H18V17M18 13H9V11H18V13M18 9H9V7H18V9Z" />
                            </svg>
                        </x-web::button>
                    </a>
                @endif
            </div>
        </div>
        {{-- @include('web::partials.no-data', ['class' => 'flex flex-col justify-center items-center']) --}}
        <div x-data="infiniteScroll()" x-init="init">
            <div id="item-list"
                class="grid gap-4 md:gap-8 {{ request()->view == 'list' ? 'md:grid-cols-2' : 'md:grid-cols-3' }}">
                {{-- @foreach ($blog_content as $blog)
                    @include('web::partials.blog-card', [
                        'blog' => $blog,
                        'lang' => $lang,
                        'isCompact' => request()->view == 'list',
                    ])
                @endforeach --}}
            </div>
            <div x-show="loading"
                class="mt-8 grid gap-4 md:gap-8 {{ request()->view == 'list' ? 'md:grid-cols-2' : 'md:grid-cols-3' }}">
                <div class="animate-pulse bg-muted rounded-lg h-96"></div>
                <div class="animate-pulse bg-muted rounded-lg h-96"></div>
                <div class="animate-pulse bg-muted rounded-lg h-96"></div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        // function infiniteScroll() {
        //     return {
        //         page: 1,
        //         loading: false,
        //         init() {
        //             window.addEventListener('scroll', () => {
        //                 if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !this
        //                     .loading) {
        //                     this.loadMore();
        //                 }
        //             });
        //         },
        //         loadMore() {
        //             this.page++;
        //             //jika loading selesai ganti ke false
        //             this.loading = true;
        //             {{-- Ambil data dari halaman selanjutnya dan tambahkan ke dalam elemen #item-list --}}
        //         }
        //     }
        // }

        function truncateString(str, maxLength) {
            if (str.length > maxLength) {
                return str.slice(0, maxLength) + '...'; // Memotong string dan menambahkan '...'
            }
            return str; // Jika tidak lebih dari maxLength, kembalikan string aslinya
        }

        function formatDate(isoDate) {
            // Mengonversi string ISO ke objek Date
            const date = new Date(isoDate);

            const day = date.getDate();
            const month = date.toLocaleString('default', {
                month: 'long'
            });
            const year = date.getFullYear();

            return `${day} ${month} ${year}`;
        }

        function infiniteScroll() {
            return {
                page: 1,
                loading: false,
                searchQuery: '{{isset($_GET['search']) ? str_replace('#', '', $_GET['search']) : ''}}',
                checklistQuery: '',
                sortBy: '', // Tambahkan variabel untuk menyimpan sorting
                init() {
                    window.addEventListener('scroll', () => {
                        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500 && !this
                            .loading) {
                            this.loadMore();
                        }
                    });

                    document.getElementById('search-input').addEventListener('input', (event) => {
                        this.searchQuery = event.target.value; // Set search query
                        this.page = 1; // Reset ke halaman 1 saat pencarian berubah
                        document.getElementById('item-list').innerHTML = ''; // Bersihkan konten lama
                        this.loadMore(); // Load ulang data sesuai pencarian
                    });

                    document.getElementById('tgl').addEventListener('click', () => {
                        this.sortBy = 'tgl'; // Atur sorting berdasarkan tanggal
                        this.page = 1; // Reset halaman ke 1
                        document.getElementById('item-list').innerHTML = ''; // Bersihkan konten lama
                        this.loadMore(); // Load ulang data sesuai sorting
                    });

                    document.getElementById('order-asc').addEventListener('click', () => {
                        this.sortBy = 'alphabetical'; // Atur sorting berdasarkan alfabet
                        this.page = 1; // Reset halaman ke 1
                        document.getElementById('item-list').innerHTML = ''; // Bersihkan konten lama
                        this.loadMore(); // Load ulang data sesuai sorting
                    });

                    const checkboxes = document.querySelectorAll('input[type="checkbox"][id="events"]');
                    checkboxes.forEach(checkbox => {
                        // Event change untuk setiap checkbox
                        checkbox.addEventListener('change', () => {
                            this.updateChecklistQuery(
                                checkboxes); // Update checklist query ketika salah satu checkbox berubah
                            this.page = 1; // Reset halaman ke 1 jika checkbox berubah
                            document.getElementById('item-list').innerHTML = ''; // Bersihkan konten lama
                            this.loadMore(); // Load ulang data sesuai checklist
                        });
                    });
                },
                updateChecklistQuery(checkboxes) {
                    this.checklistQuery = Array.from(checkboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value);
                },
                loadMore() {
                    if (this.page === null || this.loading) return;

                    this.loading = true;
                    let itemList = document.getElementById('item-list');
                    const rootUrl = "{{ url('/') }}";


                    fetch("{{ route('web::load.blog', ['bahasa' => $lang]) }}" +
                            `?page=${this.page}&search=${this.searchQuery}&checklist=${this.checklistQuery}&sort=${this.sortBy}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            if (data.data.length > 0) {
                                data.data.forEach(item => {
                                    const imageUrl = `${rootUrl}/uploads/${item.location}/${item.image}`;
                                    const langx = "{{ $lang }}";
                                    const json_data = JSON.parse(item.content).{{ $lang }};
                                    const categoryTitle = data.category[item.id] ??
                                        'Uncategory'; // Pastikan categoryTitle ada

                                    // Reset html untuk setiap item baru
                                    let newItem = document.createElement('div');
                                    @if (isset($_GET['view']))
                                        newItem.innerHTML = `
                                    <a href="${rootUrl}/${langx}/blog/${json_data.slug}">
                                        <div class="p-2 rounded-md border border-border flex items-center gap-4 justify-between hover:shadow transition-shadow">
                                            <h4 class="font-medium line-clamp-2 flex-1">
                                                ${json_data.title}
                                                ${truncateString(json_data.post0, 100)}
                                            </h4>
                                            <div class="flex items-center gap-2">
                                            <x-web::badge>${categoryTitle}</x-web::badge>
                                             <span class="text-muted-foreground text-sm">
                                                ${formatDate(item.created_at)}
                                             </span>
                                            </div>
                                        </div>
                                    </a>
                                `;
                                    @else
                                        newItem.innerHTML = `
                                    <a href="${rootUrl}/${langx}/blog/${json_data.slug}">
                                        <div class="p-4 rounded-lg border border-border flex gap-4 flex-col group hover:shadow-md transition-shadow h-full">
                                            <div class="w-full h-56 overflow-hidden rounded-lg">
                                                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover rounded-lg" src="${imageUrl}" alt="Blog Image">
                                            </div>
                                            <x-web::badge>${categoryTitle}</x-web::badge>
                                            <h4 class="text-xl font-bold line-clamp-2">${json_data.title}</h4>
                                            <p class="line-clamp-3 text-sm">${truncateString(json_data.post0, 100)}</p>
                                            <span class="text-muted-foreground text-sm mt-auto">${formatDate(item.created_at)}</span>
                                        </div>
                                    </a>
                                `;
                                    @endif

                                    itemList.appendChild(newItem);
                                });

                                if (data.next_page_url) {
                                    this.page++;
                                } else {
                                    this.page = null;
                                }
                            } else {
                                this.page = null;
                            }

                            this.loading = false;
                        })
                        .catch((error) => {
                            console.error('Error fetching data:', error);
                            this.loading = false;
                        });
                }
            }
        }


        // Inisialisasi infinite scroll saat halaman dimuat
        // document.addEventListener('DOMContentLoaded', function() {
        //     const scrollInstance = infiniteScroll();
        //     scrollInstance.init();
        // });
    </script>
@endpush

@extends('web::layouts.default')
@section('title', 'Karir | ')

{{-- @php($data = [[], []]) --}}

@section('main')
    <section class="container mx-auto md:px-16 py-16">
        <div class="mb-8 space-y-2">
            <h2 class="font-bold max-w-lg text-lg">{{ get_content_json($career_detail)[$lang]['title'] }}</h2>
            <div class="flex items-center gap-1">
                <x-web::badge>{{ category_by_post_id($career_detail->id, '1812836995295365')->title }}</x-web::badge>
                <x-web::badge
                    variant="success">{{ category_by_post_id($career_detail->id, '1812836908171625')->title }}</x-web::badge>
                <span class="text-muted-foreground text-sm flex gap-1 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" fill="currentColor">
                        <path
                            d="M12,6.5A2.5,2.5 0 0,1 14.5,9A2.5,2.5 0 0,1 12,11.5A2.5,2.5 0 0,1 9.5,9A2.5,2.5 0 0,1 12,6.5M12,2A7,7 0 0,1 19,9C19,14.25 12,22 12,22C12,22 5,14.25 5,9A7,7 0 0,1 12,2M12,4A5,5 0 0,0 7,9C7,10 7,12 12,18.71C17,12 17,10 17,9A5,5 0 0,0 12,4Z" />
                    </svg> {{ get_content_json($career_detail)[$lang]['post3'] }}</span>
            </div>

            <span class="text-sm text-muted-foreground flex gap-2 items-center pt-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="currentColor">
                    <path
                        d="M9 8H11V14H9V8M13 1H7V3H13V1M17.03 7.39C18.26 8.93 19 10.88 19 13C19 17.97 15 22 10 22C5.03 22 1 17.97 1 13S5.03 4 10 4C12.12 4 14.07 4.74 15.62 6L17.04 4.56C17.55 5 18 5.46 18.45 5.97L17.03 7.39M17 13C17 9.13 13.87 6 10 6S3 9.13 3 13 6.13 20 10 20 17 16.87 17 13M21 7V13H23V7H21M21 17H23V15H21V17Z" />
                </svg>
                {{ $lang == 'id' ? 'Akan Ditutup' : 'Will be closed in' }}
                <?php
                $date1 = new DateTime('now');
                $date2 = new DateTime(get_content_json($career_detail)[$lang]['post1']);
                $interval = $date1->diff($date2);
                echo $interval->days;
                ?>

                {{ $lang == 'id' ? 'Hari Lagi' : 'More days' }}
            </span>
        </div>
        <hr class="border-border mb-6" />
        {!! str_replace(
            '<p>',
            "<p class='text-justify'>",
            preg_replace('/<span[^>]+\>/i', '', get_content_json($career_detail)[$lang]['post2']),
        ) !!}

        {{-- <ul class="space-y-4">
            <li class="space-y-2">
                <h4 class="text-sm font-semibold">Deskripsi Pekerjaan</h4>
                <p>
                    Deserunt laborum dolor magna est sint ipsum nostrud irure labore. Enim cupidatat esse dolore ea aliquip
                    esse
                    sint id laboris. Velit do excepteur eiusmod duis. Commodo est est nostrud ipsum do officia eiusmod est
                    laboris
                    ipsum. Commodo qui irure aliqua elit. In eu dolore anim cillum cupidatat. Aute consequat ipsum qui
                    consectetur
                    anim proident aute eu velit ex minim est.</br />

                    Id magna id dolore voluptate veniam velit ad nulla deserunt consectetur est. Ullamco do dolor ad dolor
                    cupidatat
                    est non laborum ea aliqua cupidatat. Aute ea consectetur ad deserunt aliquip enim fugiat mollit in
                    cillum
                    cupidatat. Mollit ut mollit nostrud officia minim eu aute aliqua culpa. Est enim aliqua reprehenderit
                    tempor
                    deserunt. Nulla et excepteur ut ea fugiat et est ea cupidatat consectetur.
                </p>
            </li>
            <li class="space-y-2">
                <h4 class="text-sm font-semibold">Persyaratan</h4>
                <p>
                    Deserunt laborum dolor magna est sint ipsum nostrud irure labore. Enim cupidatat esse dolore ea aliquip
                    esse sint id laboris. Velit do excepteur eiusmod duis. Commodo est est nostrud ipsum do officia eiusmod
                    est laboris ipsum. Commodo qui irure aliqua elit. In eu dolore anim cillum cupidatat. Aute consequat
                    ipsum
                    qui consectetur anim proident aute eu velit ex minim est.
                </p>
                <ol class="space-y-1">
                    <li>Deserunt laborum dolor magna est sint ipsum nostrud irure labore.</li>
                    <li>Enim cupidatat esse dolore ea aliquip esse sint id laboris.</li>
                    <li>Velit do excepteur eiusmod duis.</li>
                    <li>Commodo est est nostrud ipsum do officia eiusmod est laboris ipsum.</li>
                    <li>Commodo qui irure aliqua elit.</li>
                    <li>In eu dolore anim cillum cupidatat.</li>
                    <li>Aute consequat ipsum qui consectetur anim proident aute eu velit ex minim est.</li>
                </ol>

            </li>
            <li class="space-y-2">
                <h4 class="text-sm font-semibold">Benefit</h4>
                <p>
                    Deserunt laborum dolor magna est sint ipsum nostrud irure labore. Enim cupidatat esse dolore ea aliquip
                    esse sint id laboris. Velit do excepteur eiusmod duis. Commodo est est nostrud ipsum do officia eiusmod
                    est laboris ipsum. Commodo qui irure aliqua elit. In eu dolore anim cillum cupidatat. Aute consequat
                    ipsum
                    qui consectetur anim proident aute eu velit ex minim est.
                </p>
            </li>
        </ul> --}}
    </section>

    <section id="apply" class="container mx-auto md:px-16 py-16">
        {{-- <h3 class="font-bold text-lg mb-8">Lamar Sekarang</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label for="full-name" class="w-fit pl-0.5 text-xs">Nama Lengkap</label>
                <x-web::input id="full-name" type="text" name="full-name" placeholder="John Doe" autocomplete="name"
                    required />
            </div>
            <div class="space-y-1">
                <label for="phone" class="w-fit pl-0.5 text-xs">Nomor Ponsel</label>
                <x-web::input id="phone" type="number" name="phone" placeholder="08135454666" autocomplete="phone"
                    required minlength="10" maxlength="13" />
            </div>
            <div class="space-y-1">
                <label for="email" class="w-fit pl-0.5 text-xs">Alamat Email</label>
                <x-web::input id="email" type="email" name="email" placeholder="johndoe@gmail.com"
                    autocomplete="email" required />
            </div>

            <div class="space-y-1">
                <label for="email" class="w-fit pl-0.5 text-xs">Alamat Domisili</label>
                <x-web::input id="address" type="text" name="address" placeholder="Jln. Terserah No. 1"
                    autocomplete="address" />
            </div> --}}

            {{-- <div class="flex items-center justify-center w-full col-span-2">
                <div x-data="fileDrop()" x-init="init()" @drop.prevent="handleDrop" @dragover.prevent
                    @dragenter.prevent
                    class="flex flex-col items-center justify-center w-full h-64 border-2 border-border border-dashed rounded-lg cursor-pointer bg-muted/50 dark:hover:bg-muted hover:border-foreground/30">
                    <label for="cv-file" class="flex flex-col items-center justify-center w-full h-full">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-muted-foreground" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                            </svg>
                            <p class="mb-2 text-sm text-muted-foreground">Klik atau tarik dan lepas<span class="font-bold">
                                    file CV anda </span>disini</p>
                            <p class="text-xs text-muted-foreground">PDF, DOC, DOCX, JPG, JPEG, PNG, Max 2MB</p>
                        </div>
                        <input id="cv-file" name="cv-file" type="file" class="hidden" @change="handleFiles($event)"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                    </label>
                    <template x-if="selectedFile">
                        <p class="text-xs mb-4">File terpilih: <span x-text="selectedFile.name"></span>
                        </p>
                    </template>
                </div>

            </div> --}}
        {{-- </div> --}}
        @livewire('web::global.form-career', ['careerId' => @$career_detail->id]) 
    </section>

    {{-- @livewire('web::global.form-career', ['careerId' => @$career_detail->id]) --}}
@endsection

@push('scripts')
    <script>
        function fileDrop() {
            return {
                selectedFile: null,
                init() {
                    const fileInput = document.getElementById('cv-file');
                    fileInput.addEventListener('change', (e) => {
                        this.handleFiles(e);
                    });
                },
                handleDrop(event) {
                    const files = event.dataTransfer.files;
                    if (files.length > 0) {
                        this.selectedFile = files[0];
                        this.updateFileInput(files[0]);
                    }
                },
                handleFiles(event) {
                    const files = event.target.files;
                    if (files.length > 0) {
                        this.selectedFile = files[0];
                    }
                },
                updateFileInput(file) {
                    const fileInput = document.getElementById('cv-file');
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                }
            };
        }
    </script>
@endpush

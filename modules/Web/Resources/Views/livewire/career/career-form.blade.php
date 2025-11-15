<div>
    <section id="apply" class="container mx-auto md:px-16 py-16">
        <h3 class="font-bold text-lg mb-8">Lamar Sekarang</h3>
        <form wire:submit="submitForm">
            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="full-name" class="w-fit pl-0.5 text-xs">Nama Lengkap</label>
                    <x-web::input id="full-name" wire:model="fname" type="text" name="full-name" placeholder="John Doe" autocomplete="name"
                        required />
                </div>
                <div class="space-y-1">
                    <label for="phone" class="w-fit pl-0.5 text-xs">Nomor Ponsel</label>
                    <x-web::input id="phone" wire:model="fphone" type="number" name="phone" placeholder="08135454666" autocomplete="phone"
                        required minlength="10" maxlength="13" />
                </div>
                <div class="space-y-1">
                    <label for="email" class="w-fit pl-0.5 text-xs">Alamat Email</label>
                    <x-web::input id="email" wire:model="femail" type="email" name="email" placeholder="johndoe@gmail.com"
                        autocomplete="email" required />
                </div>

                <div class="space-y-1">
                    <label for="email" class="w-fit pl-0.5 text-xs">Alamat Domisili</label>
                    <x-web::input id="address" wire:model="faddress" type="text" name="address" placeholder="Jln. Terserah No. 1"
                        autocomplete="address" />
                </div>

                <div class="flex items-center justify-center w-full col-span-2">
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
                </div>

                <div class="space-y-1">
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-6 py-2 gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" fill="currentColor" stroke="currentColor">
                         <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z"></path>
                     </svg>
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </section>

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
</div>
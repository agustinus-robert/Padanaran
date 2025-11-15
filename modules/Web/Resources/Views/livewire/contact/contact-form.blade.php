<div>
    <form x-data="{ tab: @entangle('tab') }" class="space-y-4" wire:submit="save">
        <div class="flex items-center gap-4 p-1 rounded-lg bg-muted w-fit">
            <button type="button" class="px-6 py-1.5 rounded-md flex items-center gap-2"
                wire:click="tabAction('individual')"
                :class="{ 'bg-primary text-primary-foreground': tab === '{{$tab}}', 'bg-secondary text-secondary-foreground': tab === 'organization' }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="currentColor"
                    stroke="currentColor">
                    <path
                        d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                </svg>
                Individu
            </button>
            <button type="button" class="px-6 py-1.5 rounded-md flex items-center gap-2"
                wire:click="tabAction('organization')"
                :class="{ 'bg-primary text-primary-foreground': tab === '{{$tab}}', 'bg-secondary text-secondary-foreground': tab === 'individual' }">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-5" fill="currentColor"
                    stroke="currentColor">
                    <path
                        d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z" />
                </svg>
                Organisasi
            </button>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div x-show="tab==='individual'" class="space-y-1">
                <label for="first-name" class="w-fit pl-0.5 text-xs">Nama Depan</label>
                <x-web::input id="first-name" wire:model="first_name" type="text" name="first-name" placeholder="Masukkan nama depan"
                    autocomplete="name" x-bind:required="tab === 'individual'" />
            </div>
            <div x-show="tab==='individual'" class="space-y-1">
                <label for="last-name" class="w-fit pl-0.5 text-xs">Nama Belakang</label>
                <x-web::input id="last-name" wire:model="last_name" type="text" name="last-name" placeholder="Masukkan nama belakang"
                    autocomplete="name" x-bind:required="tab === 'individual'" />
            </div>
            <div x-show="tab==='organization'" class="space-y-1 col-span-2">
                <label for="organization-name" class="w-fit pl-0.5 text-xs">Nama Organisasi</label>
                <x-web::input id="organization-name" wire:model="org" type="text" name="organization-name"
                    placeholder="Masukkan organisasi" autocomplete="name"
                    x-bind:required="tab === 'organization'" />
            </div>
            <div class="space-y-1">
                <label for="email" class="w-fit pl-0.5 text-xs">Email</label>
                <x-web::input id="email" type="email" wire:model="email" name="email" placeholder="Masukan alamat email"
                    autocomplete="email" required />
            </div>
            <div class="space-y-1">
                <label for="subject" class="w-fit pl-0.5 text-xs">Subjek</label>
                <x-web::input id="subject" type="text" wire:model="subject" name="subject" placeholder="Masukkan subjek pesan"
                    required />
            </div>
            <div class="space-y-1 col-span-2">
                <label for="message" class="w-fit pl-0.5 text-xs">Pesan</label>
                <x-web::textarea id="message" wire:model="message" type="text" name="message" rows="5"
                    placeholder="Tuliskan pesan anda disini" required />
            </div>
        </div>
        <x-web::button class="gap-2" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" fill="currentColor"
                stroke="currentColor">
                <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
            </svg>
            Kirim Pesan
        </x-web::button>
    </form>
</div>
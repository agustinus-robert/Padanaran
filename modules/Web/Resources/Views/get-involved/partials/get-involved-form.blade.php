  @if($role != 2)
 <p>{{ $label }}
 </p>

 <div class="grid md:grid-cols-2 gap-4">
    <div class="space-y-1">
        <label for="first-name" class="w-fit pl-0.5 text-xs">Nama Depan</label>
        <x-web::input id="first-name" type="text" wire:model="first_name" name="first-name" placeholder="John" autocomplete="name"
            required />
    </div>
    <div class="space-y-1">
        <label for="last-name" class="w-fit pl-0.5 text-xs">Nama Belakang</label>
        <x-web::input id="last-name" type="text" wire:model="last_name" name="last-name" placeholder="Doe" autocomplete="name"
            required />
    </div>
    <div class="space-y-1">
        <label for="phone" class="w-fit pl-0.5 text-xs">Nomor Ponsel</label>
        <x-web::input wire:model="phone" id="phone" type="number" name="phone" placeholder="08135454666"
            autocomplete="phone" required minlength="10" maxlength="13" />
    </div>
    <div class="space-y-1">
        <label for="email" class="w-fit pl-0.5 text-xs">Alamat Email</label>
        <x-web::input wire:model="email" id="email" type="email" name="email" placeholder="johndoe@gmail.com"
            autocomplete="email" required />
    </div>
    <div class="space-y-1">
        <label for="address" class="w-fit pl-0.5 text-xs">Alamat</label>
        <x-web::textarea wire:model="address" id="address" type="text" name="address"
            placeholder="Jln. Terserah No. 1, RT 01 RW 01, Kelurahan Terserah, Kecamatan Terserah, Kota Terserah, Provinsi Terserah, Kode Pos 12345"
            autocomplete="address" rows="5" required />
    </div>
    <div class="space-y-1">
        <label for="note" class="w-fit pl-0.5 text-xs">Catatan</label>
        <x-web::textarea wire:model="notes" id="note" type="text" name="note" placeholder="Donasi ini... "
            autocomplete="note" rows="5" />
    </div>
</div>
@endif
 <x-web::button type="submit" class="gap-2">
     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="size-4" fill="currentColor"
         stroke="currentColor">
         <path d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
     </svg>
     {{ $buttonLabel }}
 </x-web::button>

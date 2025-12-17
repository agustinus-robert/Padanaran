<div id="content-block">
    {{-- EMAIL --}}
    <x-input-group :isRow="true">
        <x-label value="Alamat surel" required />
        <x-col size="10">
            <x-input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
            />
        </x-col>
    </x-input-group>

    {{-- STATUS VERIFIKASI --}}
    @if ($user->email)
        <div class="mb-3">
            <div class="mb-1 small text-muted">Status verifikasi</div>

            @if ($user->email_verified_at)
                <span class="badge bg-success d-inline-flex align-items-center gap-1 px-2 py-1 small">
                    <span class="material-symbols-rounded" style="font-size:14px">
                        check
                    </span>
                    Terverifikasi
                </span>
            @else
                <span class="badge bg-danger d-inline-flex align-items-center gap-1 px-2 py-1 small mb-1">
                    <span class="material-symbols-rounded" style="font-size:14px">
                        close
                    </span>
                    Belum terverifikasi
                </span>

                <div class="mt-3">
                    <a
                        id="reverify"
                        href="{{ route('account::user.email.reverify', [
                            'uid' => encrypt($user->id),
                            'next' => ($next ?? route('account::index'))
                        ]) }}"
                        class="d-inline-flex align-items-center gap-1 small"
                    >
                        <span class="material-symbols-rounded" style="font-size:14px">email</span>
                        Klik untuk kirim tautan verifikasi
                    </a>
                </div>
            @endif
        </div>
    @endif


    {{-- ACTIONS --}}
    <div class="d-flex gap-2 mt-2">
        <x-btn variant="dark">
            <span class="material-symbols-rounded">
                check
            </span> Simpan
        </x-btn>

        @isset($back)
            <a
                class="btn btn-ghost-light text-dark"
                href="{{ request('next', route('account::index')) }}"
            >
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>
        @endisset
    </div>

    {{-- WARNING --}}
    @if ($user->email_verified_at)
        <hr class="text-muted">
        <p class="mb-0">
            <strong>Peringatan!</strong><br>
            Jika Anda mengubah alamat surel {{ $user->display_name }},
            kami akan melakukan verifikasi ulang terhadap surel tersebut.
        </p>
    @endif
</div>

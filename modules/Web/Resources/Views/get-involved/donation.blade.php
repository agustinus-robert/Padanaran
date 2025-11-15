@extends('web::layouts.about', ['headerTitle' => 'Donasi'])
@section('title', 'Donasi | ')


@section('content')
    @livewire('web::donate.form-donation', ['invoice_id' => @$invoice])
    {{-- <section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold">Berdonasilah hari ini!</h1>
            <p class="max-w-4xl text-muted-foreground">
                Dukungan Anda sangat diperlukan.</p>
        </div>
        <p>Berapa jumlah yang ingin anda donasikan?</p>
        <form class="space-y-8">
            <div x-data="{ amount: 0 }" class="grid grid-cols-2 gap-4 max-w-6xl">
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 0"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            0,
                    }">
                    <p>
                        Berdonasi Rp 50.000 sama halnya dengan menolong seorang anak membeli
                        <strong>pakaian seragam</strong>
                    </p>
                </button>
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 1"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            1,
                    }">
                    <p>
                        Berdonasi Rp 500.000 sama halnya dengan membantu seorang murid membeli <strong>sepeda</strong> untuk
                        perjalanan
                        jauhnya menuju sekolah
                    </p>
                </button>
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 2"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            2,
                    }">
                    <p>
                        Berdonasi Rp 5.000.000 sama halnya dengan membelikan sebuah <strong>kaki buatan untuk seorang anak
                            cacat</strong>
                    </p>
                </button>
                <button x-data="rupiahFormatter()" type="button" class="rounded-lg border border-border p-4"
                    x-on:click="amount = 3"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            3,
                    }">
                    <p x-show="amount !== 3">
                        Anda dapat menentukan sendiri jumlah donasi yang ingin diberikan sesuai dengan kemampuan anda
                    </p>
                    <input x-show="amount === 3" type="text" x-model="inputValue" @input="formatRupiah"
                        class="w-full bg-transparent focus:outline-none text-xl font-semibold"
                        :class="amount === 3 ? 'dark:placeholder:text-gray-400 placeholder:text-gray-300' :
                            'placeholder:text-secondary-foreground'"
                        x-bind:placeholder="amount === 3 ? 'Rp 50.000.000' : 'Jumlah lainnya'">
                </button>
            </div>
            <p class="text-muted-foreground">
                Jika Anda menginginkan penyaluran spesifik atas donasi Anda untuk program tertentu, silakan
                memberitahukannya kepada kami. Kami akan memasukkan donasi tersebut ke dana umum apabila tidak ada
                pemberitahuan sebelumnya.<br />Donasi yang Anda lakukan, baik besar maupun kecil, akan menciptakan perbedaan
                yang begitu besar.</p>
            @include('web::get-involved.partials.get-involved-form', [
                'label' => 'Silahkan masukan informasi data diri anda untuk proses donasi.',
                'buttonLabel' => 'Donasi Sekarang',
            ])
        </form>
    </section> --}}
@endsection

@push('scripts')
    <script>
        function rupiahFormatter() {
            return {
                inputValue: '',
                formatRupiah() {
                    if (this.inputValue === '') return;

                    let value = this.inputValue.replace(/[^,\d]/g, '');

                    let formattedValue = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);

                    this.inputValue = formattedValue.replace('Rp', 'Rp ');
                }
            };
        }
    </script>
@endpush

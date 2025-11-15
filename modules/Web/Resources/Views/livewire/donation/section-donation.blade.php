<div>
    <section class="container mx-auto md:px-16 py-16 space-y-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-bold">{{$lang == 'id' ? 'Berdonasilah hari ini!' : 'Make a donation today!'}}</h1>
            <p class="max-w-4xl text-muted-foreground">
                {{$lang == 'id' ? 'Dukungan Anda sangat diperlukan.' : 'Your support is needed.'}}</p>
        </div>
        <p>{{$lang == 'id' ? 'Berapa jumlah yang ingin anda donasikan?' : 'Choose Amount donation'}}</p>
        <form class="space-y-8" wire:submit="save">
            <div x-data="{ amount: @entangle('amount') }" class="grid grid-cols-2 gap-4 max-w-6xl">
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 0"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            0,
                    }">
                    <p>
                        {{$lang == 'id' ? 'Berdonasi Rp 50.000 sama halnya dengan menolong seorang anak membeli' : 'Rp 50.000 can buy an'}}
                        <strong>{{$lang == 'id' ? 'pakaian seragam' : 'elementary school uniform.'}}</strong>
                    </p>
                </button>
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 1"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            1,
                    }">
                    <p>
                        {{$lang == 'id' ? 'Berdonasi Rp 500.000 sama halnya dengan membantu seorang murid membeli' : 'Berdonasi Rp 500.000 can buy a'}}
                         <strong>{{$lang == 'id' ? 'sepeda' : 'bicycle'}}</strong> {{$lang == 'id' ? 'untuk
                        perjalanan
                        jauhnya menuju sekolah' : 'for him to go to school.'}}
                    </p>
                </button>
                <button type="button" class="rounded-lg border border-border p-4" x-on:click="amount = 2"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            2,
                    }">
                    <p>
                        {{$lang == 'id' ? 'Berdonasi Rp 5.000.000 sama halnya dengan membelikan sebuah' : 'Rp 5.000.000 can buy an'}} <strong>{{$lang == 'id' ? 'kaki buatan untuk seorang anak
                            cacat' : 'artificial leg for student with disability.'}}</strong>
                    </p>
                </button>
                <button x-data="rupiahFormatter()" type="button" class="rounded-lg border border-border p-4"
                    x-on:click="amount = 3"
                    :class="{
                        'bg-primary text-primary-foreground': amount ===
                            3,
                    }">
                    <p x-show="amount !== 3">
                        {{$lang == 'id' ? 'Anda dapat menentukan sendiri jumlah donasi yang ingin diberikan sesuai dengan kemampuan anda' : 'You can determine the amount of donation you wish to give according to your ability.'}}
                        
                    </p>
                    <input x-show="amount === 3" type="text" x-model="inputValue" @input="formatRupiah"
                        class="w-full bg-transparent focus:outline-none text-xl font-semibold"
                        :class="amount === 3 ? 'dark:placeholder:text-gray-400 placeholder:text-gray-300' :
                            'placeholder:text-secondary-foreground'"
                        x-bind:placeholder="amount === 3 ? 'Rp 50.000.000' : '{{$lang == 'id' ? 'Jumlah lainnya' : 'Other Amount'}}'">
                </button>
            </div>
            <p class="text-muted-foreground">
               {!!$lang == 'id' ? 'Jika Anda menginginkan penyaluran spesifik atas donasi Anda untuk program tertentu, silakan
                memberitahukannya kepada kami. Kami akan memasukkan donasi tersebut ke dana umum apabila tidak ada
                pemberitahuan sebelumnya.<br />Donasi yang Anda lakukan, baik besar maupun kecil, akan menciptakan perbedaan
                yang begitu besar.' : 'If you wish your donations to go towards a specific project, please write a message.<br />Otherwise, your donations will be directed into our general fund.'!!} </p>
        
                @include('web::get-involved.partials.get-involved-form', [
                    'label' => 'Silahkan masukan informasi data diri anda untuk proses donasi.',
                    'buttonLabel' => 'Donasi Sekarang',
                    'role' => (isset(\Auth::user()->role->role_id) ?  \Auth::user()->role->role_id : '')
                ])
        </form>
    </section>

    @push('scripts')
    {{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{env('MIDTRANS_CLIENT_KEY')}}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
                // var money = $('#valueMoney').val();
                // if(money){
                    Livewire.dispatch('call-first');
                    window.addEventListener('contentChanged', event => {
                            snap.pay(event.detail[0].snapToken, {
                      // Optional
                      onSuccess: function(result){
                            
                        Livewire.dispatch('payment', {status: 'success', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                            //Livewire.dispatch('payment-success', {refreshPosts: true})
                        
                      },
                      // Optional
                      onPending: function(result){
                        Livewire.dispatch('payment', {status: 'pending', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                      },
                      // Optional
                      onError: function(result){
                        Livewire.dispatch('payment', {status: 'error', paymentid: event.detail[0].pay_id, amount: result.gross_amount, order_id: result.order_id, payment_type: result.payment_type, transaction_status: result.transaction_status, transaction_id: result.transaction_id, detail: result})
                      }
                    });
                })
            // } else {
            //     Swal.fire({
            //       icon: "error",
            //       title: "Pembayaran Gagal!",
            //       text: "Masukkan nilai nominal donasi untuk melanjutkan pembayaran",
            //     });
            // }
        };  
      
    </script> --}}
    @endpush
</div>
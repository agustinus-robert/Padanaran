@extends('web::layouts.about', ['headerTitle' => 'Menjadi Sukarelawan'])
@section('title', 'Menjadi Sukarelawan | ')

@section('content')
    {{-- @livewire('web::donate.form-donation', ['invoice_id' => @$invoice]) --}}
    @livewire('web::global.form-volunteer')
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

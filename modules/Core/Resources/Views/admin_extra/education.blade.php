@extends('core::layouts.default')

@section('title', 'Jenjang Pendidikan | ')
@section('navtitle', 'Jenjang Pendidikan')

@push('styles')
    <style>
        .role-card .card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .role-card input[type="radio"]:checked + .card {
            border: 2px solid #0d6efd; /* bootstrap primary */
            box-shadow: 0 0 10px rgba(13,110,253,0.4);
        }
    </style>
@endpush

@section('content')

    <div class="col-12 p-2">
        @if (Session::has('status'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 1500)" x-show="show">
                <div class="alert alert-success">
                    {!! Session::get('status') !!}
                </div>
            </div>
        @endif 
    </div>

    <form action="{{route('core::admin-extra.choose.extra-education.store')}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <label class="w-100 role-card">
                    <input type="radio" name="education_id" value="4" class="d-none" required @checked(session('selected_grade') == 4)>
                    <div class="card mt-4 maintenance-box text-center">
                        <div class="card-body">
                            <i class="mdi mdi-school-outline mb-4 h1 text-primary"></i>
                            <h5 class="font-size-15 text-uppercase">SMP</h5>
                            <p class="text-muted mb-0">Jenjang Pendidikan SMP</p>
                        </div>
                    </div>
                </label>
            </div>

            <div class="col-md-6">
                <label class="w-100 role-card">
                    <input type="radio" name="education_id" value="5" class="d-none" required @checked(session('selected_grade') == 5)>
                    <div class="card mt-4 maintenance-box text-center">
                        <div class="card-body">
                            <i class="mdi mdi-school-outline mb-4 h1 text-primary"></i>
                            <h5 class="font-size-15 text-uppercase">SMA</h5>
                            <p class="text-muted mb-0">Jenjang Pendidikan SMA</p>
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="mdi mdi-login-variant"></i> Simpan
            </button>
        </div>
    </form>
 
@push('scripts')
    <script>
        document.querySelectorAll('.role-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.role-card .card').forEach(card => card.classList.remove('active'));
                if (this.checked) {
                    this.nextElementSibling.classList.add('active');
                }
            });
        });
    </script>
@endpush

@endsection
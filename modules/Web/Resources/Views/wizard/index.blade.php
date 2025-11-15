<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="colorlib.com">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup SASS</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="{{asset('wizard/fonts/material-icon/css/material-design-iconic-font.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pos-favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('pos-favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('pos-favicon/favicon-16x16.png') }}">
    <!-- Main css -->
    <link rel="stylesheet" href="{{asset('wizard/css/style.css')}}">
</head>

<body>

    <div class="main">

        <div class="container">
            <div class="signup-content">
                <div class="signup-desc">
                    <div class="signup-desc-content">
                        <h2><span>Pemad</span>Tenant</h2>
                        <p class="title">Step 1: Masukkan Nama Perusahaan Anda</p>
                        <p class="desc">Isikan nama perusahaan Anda untuk memulai proses pendaftaran.</p>
                        <img src="images/signup-img.jpg" alt="" class="signup-img">
                    </div>
                </div>
                <div class="signup-form-conent">
                    <form method="POST" action="{{ route('web::setup.store.page') }}" id="signup-form" class="signup-form" enctype="multipart/form-data">
                        @csrf
                        <h3></h3>
                        <fieldset>
                            <span class="step-current">Step 1 / 5</span>
                            
                            <div class="form-group">
                                <input class="cls-input" type="text" name="your_name" id="your_name" required/>
                                <label class="cls-label" for="your_name">Nama Perushaan</label>
                            </div>
                        </fieldset>
    
                        <h3></h3>
                        <fieldset>
                            <span class="step-current">Step 2 / 5</span>
                            <div class="form-group">
                                <input class="cls-input" type="text" name="email" id="email" required/>
                                <label class="cls-label" for="email">Email</label>
                            </div>
                        </fieldset>

                        <h3></h3>
                        <fieldset>
                            <span class="step-current">Step 3 / 5</span>
                            <div class="form-group">
                                <input class="cls-input" type="text" name="domain" id="domain" required/>
                                <label class="cls-label" for="email">Domain</label>
                            </div>
                        </fieldset>
{{--     
                        <h3></h3>
                        <fieldset>
                           <span class="step-current">Step 3 / 5</span>
                            @include('web::wizard.partials.list')
                        </fieldset>  --}}

                        <h3></h3>
                        <fieldset>
                            <span class="step-current">Step 4 / 5</span>
                            <div class="form-group">
                                <input class="cls-input" type="text" name="your_password" id="your_password" required/>
                                <label class="cls-label" for="your_password">Sandi</label>
                                <span toggle="#your_password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                            </div>
                        </fieldset>
    
                        <h3></h3>
                        <fieldset>
                            <span class="step-current">Step 5 / 5</span>
                            <div class="form-group">
                                <input class="cls-input" type="text" name="confirm_password" id="confirm_password" required/>
                                <label class="cls-label" for="confirm_password">Konfirmasi Kata Sandi</label>
                                <span toggle="#confirm_password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <!-- JS -->
    <script src="{{asset('wizard/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('wizard/vendor/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script src="{{asset('wizard/vendor/jquery-validation/dist/additional-methods.min.js')}}"></script>
    <script src="{{asset('wizard/vendor/jquery-steps/jquery.steps.min.js')}}"></script>
    <script src="{{asset('wizard/js/main.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    @stack('scripts')
</body>

</html>
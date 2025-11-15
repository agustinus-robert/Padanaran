<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <title>Login and Registration Form</title>
    <link rel="stylesheet" href="{{ asset('logins/styles.css') }}" />
    <meta name="viewport" content="width=device-width, 
                                   initial-scale=1.0" />

     @livewireStyles
</head>

<body>
    <div class="wrapper">
        <div class="title-text">
            <div class="title login">Sign In</div>
            <div class="title signup">Sign Up</div>
            <br /><br />
        </div>
        <div class="form-container">
            @yield('content')
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-livewire-alert::scripts />
    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('logins/script.js')}}"></script>

    @stack('scripts')
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Preview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#">

    <link href="{{ asset('cbox/assets/minimalist-blocks/content.css') }}" rel="stylesheet">
    <link href="{{ asset('cbox/box/box-flex.css') }}" rel="stylesheet">
    <link href="{{ asset('cbox/assets/scripts/glide/css/glide.core.css') }}" rel="stylesheet">
    <link href="{{ asset('cbox/assets/scripts/glide/css/glide.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('cbox/assets/scripts/navbar/navbar.css') }}" rel="stylesheet">

    <script>
        /* Add saved styles */
        if (localStorage.getItem('preview-maincss') != null) {
            document.head.insertAdjacentHTML('beforeend', localStorage.getItem('preview-maincss'));
        }
        if (localStorage.getItem('preview-sectioncss') != null) {
            document.head.insertAdjacentHTML('beforeend', localStorage.getItem('preview-sectioncss'));
        }
    </script>

</head>

<body style="touch-action: pan-y">
    <div class="is-wrapper">
        {!! $pageData !!}
    </div>

    <script>
        // // Add saved HTML
        // const wrapper = document.querySelector('.is-wrapper');
        // wrapper.innerHTML = '';
        // const range = document.createRange();
        // range.setStart(wrapper, 0);
        // wrapper.appendChild(
        //     range.createContextualFragment()
        // );
    </script>

    <script src="{{ asset('cbox/assets/scripts/glide/glide.js') }}"></script>
    <script src="{{ asset('cbox/assets/scripts/navbar/navbar.min.js') }}"></script>

    <script src="{{ asset('cbox/box/box-flex.js') }}"></script>

    <!-- Optional: if you want to add smooth scrolling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"></script>
    <script>
        SmoothScroll({
            frameRate: 150,
            animationTime: 800,
            stepSize: 120,
            pulseAlgorithm: 1,
            pulseScale: 4,
            pulseNormalize: 1,
            accelerationDelta: 300,
            accelerationMax: 2,
            keyboardSupport: 1,
            arrowScroll: 50,
            fixedBackground: 0
        });
    </script>
</body>

</html>

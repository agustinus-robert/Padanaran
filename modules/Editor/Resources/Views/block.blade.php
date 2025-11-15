<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1E1E2E; /* Mirip Studio */
            color: #FFF;
            }

            #gjs {
            border: none;
            background-color: #2A2A3A;
            }

            .gjs-pn-panel {
            background-color: #1E1E2E !important;
            border-bottom: 1px solid #333;
            }

            .gjs-pn-buttons {
            background-color: #2A2A3A !important;
            border-right: 1px solid #333;
            }

            .gjs-pn-btn {
            color: #FFF !important;
            }

            .gjs-pn-btn.gjs-pn-active {
            background-color: #535B75 !important;
            }

    </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guttenberg Laravel</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        #gjs {
            height: 100vh; /* Full screen height */
            border: none;
        }
    </style>
</head>
<body>
    <div id="editor"></div>
    <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('emailBuilder/css/main.min.css') }}" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Builder Laravel</title>
</head>

<body>
    <input type="text" id="filename" placeholder="contoh: welcome-email" style="margin: 10px; padding: 5px;" />
    <button id="export-html" style="position:fixed;top:10px;right:10px;z-index:9999;">Send Story</button>
    <div id="root"></div>
    <script>
        const FormLayoutDesign = {
            root: {
                type: "EmailLayout",
                data: {
                    backdropColor: "#F5F5F5",
                    canvasColor: "#FFFFFF",
                    textColor: "#262626",
                    fontFamily: "MODERN_SANS",
                    childrenIds: []
                }
            }
        };

        var RouteSave = '{{ route('editor::editorManage.store') }}'
    </script>
    <script type="module" id="mainJs" crossorigin src="{{ asset('emailBuilder/js/main.min.js') }}"></script>
    <script type="module" id="builderJs" crossorigin src="{{ asset('emailBuilder/js/builder.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>

<x-filament::page>
    <button onclick="saveContent()" class="bg-blue-500 text-white px-4 py-2 rounded">
        Save
    </button>

    <div id="editor"></div>

    <script src="https://editor.unlayer.com/embed.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var editor = unlayer.createEditor({
                id: "editor",
                projectId: 12345, // Bisa kosong jika tidak menggunakan project Unlayer
            });

            window.saveContent = function () {
                editor.exportHtml(function (data) {
                    let html = data.html;
                    fetch("{{ route('editor.unlayer.save') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ content: html })
                    }).then(response => response.json())
                      .then(data => alert("Saved successfully!"));
                });
            };
        });
    </script>
</x-filament::page>

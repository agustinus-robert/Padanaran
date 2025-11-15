@isset($qr)
    <div style="width: 84px; margin: 0 auto;">
        <img style="display: block; margin: 5pt 0;" src="data:image/jpeg;base64,{!! base64_encode(
            QrCode::style('round')->size(isset($size) ? $size : (isset($small) ? 64 : 80))->generate($link),
        ) !!}" alt="">
        @if (isset($withText))
            <div style="font-size: 6pt; line-height: 6pt; overflow-wrap: break-word;">{{ $qr }}</div>
        @endif
    </div>
@endisset

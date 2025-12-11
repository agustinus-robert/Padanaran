<ul class="metismenu list-unstyled" id="side-menu">

    @foreach ($menu as $item)

        {{-- === TITLE === --}}
        @if ($item['type'] === 'title')
            <li class="menu-title">{{ $item['label'] }}</li>
        @endif

        {{-- === SINGLE ITEM === --}}
        @if ($item['type'] === 'item')
            <li class="nav-main-item">
                <a class="nav-main-link" href="{{ $item['route'] }}">
                    <i class="nav-icon mdi {{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>
            </li>
        @endif

        {{-- === DROPDOWN (PARENT WITH CHILDREN) === --}}
        @if ($item['type'] === 'dropdown')
            <li class="nav-main-item">
                <a class="has-arrow waves-effect" href="javascript:void(0)">
                    <i class="nav-icon mdi {{ $item['icon'] }}"></i> {{ $item['label'] }}
                </a>

                <ul class="sub-menu mm-collapse">
                    @foreach ($item['children'] as $child)
                        <li>
                            <a class="nav-main-link" href="{{ $child['route'] }}">
                                <i class="nav-icon mdi {{ $child['icon'] }}"></i> {{ $child['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

    @endforeach

</ul>

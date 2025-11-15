@props([
    'title' => [],
    'description' => null,
    'isDarkBackground' => false,
    'class' => '',
])

<header
    class="w-full flex items-center justify-center flex-col gap-2 {{ $class }} {{ $isDarkBackground ? 'text-white' : '' }}">
    <h2 class="font-bold text-3xl">
        @foreach ($title as $t)
            @if (isset($t['isBold']))
                <span class="text-primary-text">{{ $t['content'] }}</span>
            @else
                {{ $t['content'] }}
            @endif
        @endforeach
    </h2>
    <div class="flex items-center justify-center gap-2 w-full">
        <hr class="w-32 {{ $isDarkBackground ? 'border-gray-300/80' : 'border-foreground/30' }}" />
        <div class="border p-1 {{ $isDarkBackground ? 'border-gray-300/80' : 'border-foreground/30' }}">
            <div class="border p-1 size-3 {{ $isDarkBackground ? 'border-gray-300/80' : 'border-foreground/30' }}"></div>
        </div>
        <hr class="w-32 {{ $isDarkBackground ? 'border-gray-300/80' : 'border-foreground/30' }}" />
    </div>
    @if (isset($description))
        <p class="max-w-2xl text-center">
            {{ $description }}
        </p>
    @endif
</header>

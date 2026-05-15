@props([
    'items' => [],
    'badge' => null,
])

<div class="d-flex align-items-center gap-2 flex-wrap mb-1">
    {{-- Home icon --}}
    <a href="{{ route('dashboard') }}" class="text-primary d-flex align-items-center">
        <i class="ki-outline ki-home fs-5"></i>
    </a>

    @foreach ($items as $item)
        {{-- Separator --}}
        <span class="text-muted d-flex align-items-center">
            <i class="ki-outline ki-right fs-7"></i>
        </span>

        @if (!$loop->last)
            {{-- Linked item --}}
            <a href="{{ $item['url'] ?? '#' }}" class="text-primary fw-semibold fs-7">
                {{ $item['label'] }}
            </a>
        @else
            {{-- Active (last) item --}}
            <span class="text-gray-700 fw-semibold fs-7">{{ $item['label'] }}</span>
            @if ($badge)
                <span class="badge badge-light-primary fw-semibold fs-8 px-3 py-1">{{ $badge }}</span>
            @endif
        @endif
    @endforeach
</div>
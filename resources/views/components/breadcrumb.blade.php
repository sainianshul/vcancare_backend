@props(['items' => []])

<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">
    {{-- Home Link --}}
    <li class="breadcrumb-item text-muted">
        <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
            <i class="ki-duotone ki-home fs-6 text-muted"></i>
        </a>
    </li>

    @foreach ($items as $item)
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>

        @if (!empty($item['url']))
            <li class="breadcrumb-item text-muted">
                <a href="{{ $item['url'] }}" class="text-muted text-hover-primary">{{ $item['label'] }}</a>
            </li>
        @else
            <li class="breadcrumb-item text-gray-900">{{ $item['label'] }}</li>
        @endif
    @endforeach
</ul>

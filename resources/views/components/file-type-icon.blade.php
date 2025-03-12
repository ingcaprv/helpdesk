@props(['mime'])

@php
    $icon = match(true) {
        str_starts_with($mime, 'image/') => 'image',
        in_array($mime, ['application/pdf']) => 'pdf',
        str_starts_with($mime, 'text/'),
        $mime === 'application/msword' ||
        $mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'document',
        default => 'file'
    };

    $icons = [
        'image' => [
            'viewBox' => '0 0 24 24',
            'path' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
        ],
        'pdf' => [
            'viewBox' => '0 0 24 24',
            'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        ],
        'document' => [
            'viewBox' => '0 0 24 24',
            'path' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
        ],
        'file' => [
            'viewBox' => '0 0 24 24',
            'path' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13'
        ]
    ];
@endphp

<svg
    {{ $attributes->merge(['class' => 'w-5 h-5']) }}
    fill="none"
    stroke="currentColor"
    viewBox="{{ $icons[$icon]['viewBox'] }}"
>
    <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="{{ $icons[$icon]['path'] }}"
    />
</svg>

@props(['priority'])

@php
    $colors = [
        'high' => 'bg-red-100 text-red-800',
        'medium' => 'bg-yellow-100 text-yellow-800',
        'low' => 'bg-green-100 text-green-800'
    ];
@endphp

<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $colors[$priority] ?? 'bg-gray-100 text-gray-800' }}">
    {{ ucfirst($priority) }}
</span>

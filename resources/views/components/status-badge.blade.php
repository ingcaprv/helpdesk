@props(['status'])

@php
    $colors = [
        'open' => 'bg-green-100 text-green-800',
        'closed' => 'bg-red-100 text-red-800'
    ];
@endphp

<span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
   {{ App\Enums\TicketStatus::tryFrom(strtolower($status))?->label() ?? 'Estado desconocido' }}
</span>

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Mis Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-4">
                        Nuevo Ticket
                    </a>

                    <div class="space-y-4">
                        @foreach($tickets as $ticket)
                            <div class="p-4 border rounded-lg">
                                <h3 class="text-lg font-semibold">{{ $ticket->title }}</h3>
                                <h3 class="text-lg font-semibold">{{ $ticket->user->name }}</h3>
                                <p class="text-gray-600">{{ $ticket->description }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="badge badge-{{ $ticket->status === 'open' ? 'success' : 'danger' }}">
                                        {{ strtoupper($ticket->status) }}
                                    </span>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-500 hover:underline">
                                        Ver Detalles
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

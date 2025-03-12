<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                {{ __('Mis Tickets de Soporte') }}
            </h2>
            <x-primary-link href="{{ route('tickets.create') }}">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ __('Nuevo Ticket') }}
            </x-primary-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Filtros y Búsqueda -->
                    <div class="flex flex-col mb-6 space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center space-x-4">
                            <x-text-input
                                type="search"
                                placeholder="Buscar tickets..."
                                class="w-full sm:w-64"
                            />
                            <select class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option>Todas las categorías</option>
                                @foreach(App\Enums\TicketCategory::cases() as $category)
                                    <option value="{{ $category->value }}">{{ $category->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">{{ $tickets->total() }} tickets encontrados</span>
                        </div>
                    </div>

                    <!-- Listado de Tickets -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Asunto
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Categoría
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Prioridad
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Última actualización
                                </th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                    Acciones
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($tickets as $ticket)
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $ticket->title }}</div>
                                                <div class="text-sm text-gray-500">
                                                    Creado {{ $ticket->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-status-badge :status="$ticket->status" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-sm rounded-md bg-indigo-50 text-indigo-700">
                                           {{ App\Enums\TicketCategory::from((int)$ticket->category)->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-priority-badge :priority="$ticket->priority" />
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                        {{ $ticket->updated_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                        <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Ver detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No tienes tickets creados.
                                        <a href="{{ route('tickets.create') }}" class="text-indigo-600 hover:underline">
                                            Crear nuevo ticket
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($tickets->hasPages())
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

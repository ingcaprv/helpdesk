<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-semibold leading-tight text-gray-800">
                Ticket #{{ $ticket->id }} - {{ $ticket->title }}
            </h2>
            <x-secondary-link href="{{ route('tickets.index') }}">
                ← Volver a mis tickets
            </x-secondary-link>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Encabezado del Ticket -->
                    <div class="flex flex-col gap-4 mb-8 md:flex-row md:items-center md:justify-between">
                        <div class="space-y-2">
                            <x-status-badge :status="$ticket->status" />
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span>Creado: {{ $ticket->created_at->isoFormat('LL [a las] H:mm') }}</span>
                                <span class="mx-2">•</span>
                                <span>Última actualización: {{ $ticket->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @can('update', $ticket)
                            <div class="flex gap-2">
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <x-secondary-button>
                                            Acciones
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </x-secondary-button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link href="{{ route('tickets.toggle-status', $ticket) }}">
                                            {{ $ticket->status === 'open' ? 'Cerrar Ticket' : 'Reabrir Ticket' }}
                                        </x-dropdown-link>
                                        @can('admin')
                                            <x-dropdown-link href="{{ route('tickets.assign', $ticket) }}">
                                                Asignar Ticket
                                            </x-dropdown-link>
                                        @endcan
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endcan
                    </div>

                    <!-- Detalles del Ticket -->
                    <div class="grid gap-6 mb-8 md:grid-cols-2">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Descripción</h3>
                                <p class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $ticket->description }}</p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Categoría</h3>
                                <p class="mt-1 text-gray-900">
                                    <span class="px-2 py-1 text-sm rounded-md bg-indigo-50 text-indigo-700">
                                        {{ App\Enums\TicketCategory::from($ticket->category)->label() }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Prioridad</h3>
                                <x-priority-badge :priority="$ticket->priority" class="text-sm" />
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Asignado a</h3>
                                <p class="mt-1 text-gray-900">
                                    {{ $ticket->assignedTo?->name ?? 'Sin asignar' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Adjuntos</h3>
                                <div class="mt-2 space-y-2">
                                    @forelse($ticket->attachments as $attachment)
                                        <div class="flex items-center justify-between p-2 border rounded-md">
                                            <div class="flex items-center gap-2">
                                                <x-file-type-icon :mime="$attachment->mime_type" class="w-5 h-5 text-gray-400" />
                                                <span class="text-sm">{{ $attachment->original_name }}</span>
                                            </div>
                                            <a href="{{ Storage::url($attachment->path) }}"
                                               download
                                               class="text-indigo-600 hover:text-indigo-900">
                                                Descargar
                                            </a>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500">No hay archivos adjuntos</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="pt-8 border-t border-gray-200">
                        <h3 class="mb-4 text-lg font-medium text-gray-900">Historial de Comentarios</h3>

                        <div class="space-y-6">
                            @foreach($ticket->comments as $comment)
                                <div class="p-4 border rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                            <span class="text-sm text-gray-500">•</span>
                                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($ticket->solution_comment_id === $comment->id)
                                            <span class="px-2 py-1 text-sm text-green-800 bg-green-100 rounded-full">
                                        Solución aceptada
                                    </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $comment->content }}</p>

                                    @if($comment->attachments->count())
                                        <div class="mt-4">
                                            <h4 class="mb-2 text-sm font-medium text-gray-500">Archivos adjuntos:</h4>
                                            <div class="space-y-2">
                                                @foreach($comment->attachments as $attachment)
                                                    <div class="flex items-center gap-2">
                                                        <x-file-type-icon :mime="$attachment->mime_type" class="w-4 h-4 text-gray-400" />
                                                        <a href="{{ Storage::url($attachment->path) }}"
                                                           class="text-sm text-indigo-600 hover:underline">
                                                            {{ $attachment->original_name }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Formulario de comentarios -->
                        <form method="POST"
                              action="{{ route('comments.store', $ticket) }}"
                              enctype="multipart/form-data"
                              class="mt-8 space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="content" :value="__('Nuevo comentario')" />
                                <textarea
                                    id="content"
                                    name="content"
                                    rows="4"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >{{ old('content') }}</textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="attachments" :value="__('Adjuntar archivos')" />
                                <input
                                    type="file"
                                    name="attachments[]"
                                    id="attachments"
                                    multiple
                                    class="block w-full mt-1 text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                >
                                <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                                    </svg>
                                    {{ __('Publicar comentario') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

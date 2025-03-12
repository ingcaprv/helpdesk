<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Nuevo Ticket de Soporte') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Título -->
                        <div>
                            <x-input-label for="title" :value="__('Asunto')" />
                            <x-text-input
                                id="title"
                                name="title"
                                type="text"
                                class="block w-full mt-1"
                                :value="old('title')"
                                required
                                autofocus
                                placeholder="Ej. Problema con facturación"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Categoría -->
                        <div>
                            <x-input-label for="category" :value="__('Categoría')" />
                            <select
                                id="category"
                                name="category"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="" disabled selected>{{ __('Selecciona una categoría') }}</option>
                                @foreach(App\Enums\TicketCategory::cases() as $category)
                                    <option
                                        value="{{ $category->value }}"
                                        {{ old('category') == $category->value ? 'selected' : '' }}
                                    >
                                        {{ $category->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <!-- Descripción -->
                        <div>
                            <x-input-label for="description" :value="__('Descripción detallada')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="6"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="{{ __('Describe tu problema con el mayor detalle posible...') }}"
                                required
                            >{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Adjuntos -->
                        <div>
                            <x-input-label :value="__('Archivos adjuntos')" />
                            <div class="mt-1">
                                <input
                                    type="file"
                                    name="attachments[]"
                                    id="attachments"
                                    multiple
                                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                >
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                {{ __('Formatos permitidos: JPG, PNG, PDF, DOC/DOCX. Máximo 5MB por archivo') }}
                            </p>
                            <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-4 mt-8">
                            <a href="{{ route('tickets.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">
                                {{ __('Cancelar') }}
                            </a>
                            <x-primary-button>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Crear Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

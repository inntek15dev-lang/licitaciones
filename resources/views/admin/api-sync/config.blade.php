<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($config) ? 'Editar Configuración' : 'Nueva Configuración' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form
                        action="{{ isset($config) ? route('admin.api-sync.update', $config->id) : route('admin.api-sync.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($config))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nombre</label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="name" type="text" name="name" value="{{ $config->name ?? '' }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="url">URL Base</label>
                            <input
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                id="url" type="text" name="url" value="{{ $config->url ?? '' }}">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="method">Método</label>
                            <select
                                class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                id="method" name="method">
                                <option value="GET" {{ (isset($config) && $config->method == 'GET') ? 'selected' : '' }}>
                                    GET</option>
                                <option value="POST" {{ (isset($config) && $config->method == 'POST') ? 'selected' : '' }}>POST</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="auth_type">Tipo Auth</label>
                            <select
                                class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                                id="auth_type" name="auth_type">
                                <option value="none" {{ (isset($config) && $config->auth_type == 'none') ? 'selected' : '' }}>None</option>
                                <option value="bearer" {{ (isset($config) && $config->auth_type == 'bearer') ? 'selected' : '' }}>Bearer Token</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                type="submit">
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
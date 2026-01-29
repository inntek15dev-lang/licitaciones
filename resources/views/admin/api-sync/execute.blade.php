<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ejecutar Sincronización: {{ $config->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Conectando a: {{ $config->url }}</p>
                    <div class="mt-4 p-4 bg-gray-100 rounded border">
                        <pre id="console">Iniciando...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
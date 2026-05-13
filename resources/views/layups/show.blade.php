<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('suppliers.index') }}" class="hover:text-gray-700">Suppliers</a>
            <span>/</span>
            <a href="{{ route('suppliers.show', $supplier) }}" class="hover:text-gray-700">
                {{ $supplier->name }}
            </a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $layup->name }}</span>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $layup->name }}</h1>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $layup->layers->count() }} layer(s) · Supplier: {{ $supplier->name }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}"
                   class="border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Edit
                </a>
                <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    + Add Layer
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">CLT Layers</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">Order</th>
                        <th class="px-6 py-3 text-left">Thickness</th>
                        <th class="px-6 py-3 text-left">Width</th>
                        <th class="px-6 py-3 text-left">Angle</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($layup->layers->sortBy('layer_order') as $layer)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">
                                {{ $layer->layer_order }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $layer->thickness }} mm</td>
                        <td class="px-6 py-4 text-gray-700">{{ $layer->width }} mm</td>
                        <td class="px-6 py-4 text-gray-700">{{ $layer->angle }}°</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('suppliers.layups.layers.edit', [$supplier, $layup, $layer]) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Edit</a>
                                <form action="{{ route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete layer {{ $layer->layer_order }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-400 text-sm">No layers yet.</div>
                            <a href="{{ route('suppliers.layups.layers.create', [$supplier, $layup]) }}"
                               class="mt-2 inline-block text-green-600 hover:underline text-sm">
                                + Add first layer
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('suppliers.show', $supplier) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Back to {{ $supplier->name }}</a>
        </div>
    </div>
</x-app-layout>

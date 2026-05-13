<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $layup->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">CLT Layers</h3>
            <a href="#"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Add Layer (Coming Soon)
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">Order</th>
                        <th class="px-4 py-3 text-left">Thickness</th>
                        <th class="px-4 py-3 text-left">Width</th>
                        <th class="px-4 py-3 text-left">Angle</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($layup->layers as $layer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $layer->layer_order }}</td>
                        <td class="px-4 py-3">{{ $layer->thickness }}</td>
                        <td class="px-4 py-3">{{ $layer->width }}</td>
                        <td class="px-4 py-3">{{ $layer->angle }}</td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            Available in Phase 4
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                            No layers yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('suppliers.show', $supplier) }}"
               class="text-gray-500 hover:underline">← Back to Supplier</a>
        </div>
    </div>
</x-app-layout>

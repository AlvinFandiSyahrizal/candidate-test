<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Layer — {{ $layup->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <div class="bg-white shadow rounded p-6">
            <form method="POST"
                  action="{{ route('suppliers.layups.layers.update', [$supplier, $layup, $layer]) }}">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Layer Order
                    </label>
                    <input type="number" name="layer_order"
                           value="{{ old('layer_order', $layer->layer_order) }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('layer_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Thickness
                    </label>
                    <input type="number" step="0.01" name="thickness"
                           value="{{ old('thickness', $layer->thickness) }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('thickness')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Width
                    </label>
                    <input type="number" step="0.01" name="width"
                           value="{{ old('width', $layer->width) }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('width')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Angle (°)
                    </label>
                    <input type="number" step="0.01" name="angle"
                           value="{{ old('angle', $layer->angle) }}"
                           class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    @error('angle')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Update
                    </button>
                    <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

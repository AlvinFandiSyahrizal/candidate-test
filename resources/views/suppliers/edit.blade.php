<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('suppliers.index') }}" class="hover:text-gray-700">Suppliers</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">Edit — {{ $supplier->name }}</span>
        </div>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-form-card>
            <h2 class="text-lg font-semibold text-gray-800 mb-6">Edit Supplier</h2>
            <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Supplier Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Update Supplier
                    </button>
                    <a href="{{ route('suppliers.index') }}"
                       class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </a>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>

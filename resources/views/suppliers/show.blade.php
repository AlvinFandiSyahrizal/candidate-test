<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">CLT Layups</h3>
            <a href="{{ route('suppliers.layups.create', $supplier) }}"
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Add Layup
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Layers</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($supplier->layups as $layup)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $layup->name }}</td>
                        <td class="px-4 py-3">{{ $layup->layers->count() }} layers</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                               class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}"
                               class="text-yellow-600 hover:underline">Edit</a>
                            <form action="{{ route('suppliers.layups.destroy', [$supplier, $layup]) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this layup?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                            No layups yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('suppliers.index') }}" class="text-gray-500 hover:underline">
                ← Back to Suppliers
            </a>
        </div>
    </div>
</x-app-layout>

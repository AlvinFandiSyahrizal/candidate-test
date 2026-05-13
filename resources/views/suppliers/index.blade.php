<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Suppliers
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex gap-2 items-center">
            <a href="{{ route('suppliers.import.form') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Import
            </a>
            <a href="{{ route('suppliers.create') }}"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                + Add Supplier
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Layups</th>
                        <th class="px-4 py-3 text-left">Actions</th>

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 font-medium">{{ $supplier->name }}</td>
                        <td class="px-4 py-3">{{ $supplier->layups_count }} layups</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('suppliers.show', $supplier) }}"
                               class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('suppliers.edit', $supplier) }}"
                               class="text-yellow-600 hover:underline">Edit</a>
                               <a href="{{ route('suppliers.export', $supplier) }}"
                                    class="text-green-600 hover:underline">Export</a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this supplier?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                            No suppliers yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $suppliers->links() }}</div>
    </div>
</x-app-layout>

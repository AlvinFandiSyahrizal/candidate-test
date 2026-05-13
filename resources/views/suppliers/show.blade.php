<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('suppliers.index') }}" class="hover:text-gray-700">Suppliers</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $supplier->name }}</span>
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

        {{-- Header card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $supplier->name }}</h1>
                <p class="text-sm text-gray-400 mt-1">
                    {{ $supplier->layups->count() }} layup(s) total
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('suppliers.export', $supplier) }}"
                   class="inline-flex items-center gap-2 border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Export JSON
                </a>
                <a href="{{ route('suppliers.edit', $supplier) }}"
                   class="inline-flex items-center gap-2 border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Edit
                </a>
                <a href="{{ route('suppliers.layups.create', $supplier) }}"
                   class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    + Add Layup
                </a>
            </div>
        </div>

        {{-- Layups table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">CLT Layups</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Layers</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($supplier->layups as $layup)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $layup->name }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                {{ $layup->layers->count() }} layers
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                                <a href="{{ route('suppliers.layups.edit', [$supplier, $layup]) }}"
                                   class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">Edit</a>
                                <form action="{{ route('suppliers.layups.destroy', [$supplier, $layup]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete {{ $layup->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 text-xs font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="text-gray-400 text-sm">No layups yet.</div>
                            <a href="{{ route('suppliers.layups.create', $supplier) }}"
                               class="mt-2 inline-block text-green-600 hover:underline text-sm">
                                + Add first layup
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('suppliers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Back to Suppliers</a>
        </div>
    </div>
</x-app-layout>

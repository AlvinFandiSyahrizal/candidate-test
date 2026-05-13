<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="bg-green-100 text-green-600 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Suppliers</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ \App\Models\Supplier::count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Layups</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ \App\Models\CltLayup::count() }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                <div class="bg-purple-100 text-purple-600 rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Layers</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ \App\Models\CltLayer::count() }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-base font-semibold text-gray-700 mb-4">Quick Actions</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('suppliers.index') }}"
                   class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    View Suppliers
                </a>
                <a href="{{ route('suppliers.create') }}"
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    + Add Supplier
                </a>
                <a href="{{ route('suppliers.import.form') }}"
                   class="inline-flex items-center gap-2 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Import Data
                </a>
            </div>
        </div>

        {{-- Recent Suppliers --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-base font-semibold text-gray-700">Recent Suppliers</h3>
                <a href="{{ route('suppliers.index') }}"
                   class="text-sm text-green-600 hover:underline">View all →</a>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Layups</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(\App\Models\Supplier::withCount('layups')->latest()->take(5)->get() as $supplier)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $supplier->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $supplier->layups_count }}</td>
                        <td class="px-6 py-3">
                            <a href="{{ route('suppliers.show', $supplier) }}"
                               class="text-green-600 hover:underline text-xs font-medium">View →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-center text-gray-400">
                            No suppliers yet.
                            <a href="{{ route('suppliers.create') }}"
                               class="text-green-600 hover:underline ml-1">Add one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>

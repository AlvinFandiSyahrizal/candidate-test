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

        <div class="mt-4">
            <a href="{{ route('suppliers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                ← Back to Suppliers
            </a>
        </div>

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

                <button onclick="openEditSupplierModal('{{ addslashes($supplier->name) }}')"
                        class="inline-flex items-center gap-2 border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Edit
                </button>

                <button onclick="openModal('modal-create-layup')"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    + Add Layup
                </button>

            </div>
        </div>

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

                        <td class="px-6 py-4 text-gray-400">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $layup->name }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                {{ $layup->layers->count() }} layers
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <a href="{{ route('suppliers.layups.show', [$supplier, $layup]) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    View
                                </a>

                                <button
                                    onclick="openEditLayupModal(
                                        {{ $layup->id }},
                                        '{{ addslashes($layup->name) }}'
                                    )"
                                    class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">
                                    Edit
                                </button>

                                <button
                                    onclick="openDeleteLayupModal(
                                        {{ $layup->id }},
                                        '{{ addslashes($layup->name) }}'
                                    )"
                                    class="text-red-500 hover:text-red-700 text-xs font-medium">
                                    Delete
                                </button>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">

                            <div class="text-gray-400 text-sm">
                                No layups yet.
                            </div>

                            <button
                                onclick="openModal('modal-create-layup')"
                                class="mt-2 inline-block text-green-600 hover:underline text-sm">
                                + Add first layup
                            </button>

                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>



    </div>

    <div id="modal-edit-supplier"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-edit-supplier')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Supplier
                </h3>

                <button onclick="closeModal('modal-edit-supplier')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>

                </button>

            </div>

            <form method="POST"
                  action="{{ route('suppliers.update', $supplier) }}">

                @csrf
                @method('PUT')

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Supplier Name
                    </label>

                    <input type="text"
                           id="edit-supplier-name"
                           name="name"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                </div>

                <div class="flex gap-2 pt-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Update
                    </button>

                    <button type="button"
                            onclick="closeModal('modal-edit-supplier')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>

    <div id="modal-create-layup"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-create-layup')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Add New Layup
                </h3>

                <button onclick="closeModal('modal-create-layup')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>

                </button>

            </div>

            <form method="POST"
                  action="{{ route('suppliers.layups.store', $supplier) }}">

                @csrf

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Layup Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                </div>

                <div class="flex gap-2 pt-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Save
                    </button>

                    <button type="button"
                            onclick="closeModal('modal-create-layup')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>

    <div id="modal-edit-layup"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-edit-layup')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Layup
                </h3>

                <button onclick="closeModal('modal-edit-layup')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>

                </button>

            </div>

            <form id="form-edit-layup"
                  method="POST"
                  action="">

                @csrf
                @method('PUT')

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Layup Name
                    </label>

                    <input type="text"
                           id="edit-layup-name"
                           name="name"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                </div>

                <div class="flex gap-2 pt-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Update
                    </button>

                    <button type="button"
                            onclick="closeModal('modal-edit-layup')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>


    <div id="modal-delete-layup"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-delete-layup')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center">

            <div class="flex justify-center mb-4">

                <div class="bg-red-100 rounded-full p-3">

                    <svg class="w-6 h-6 text-red-600"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>

                    </svg>

                </div>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-1">
                Delete Layup
            </h3>

            <p class="text-sm text-gray-500 mb-1">
                Are you sure you want to delete
            </p>

            <p id="delete-layup-name"
               class="text-sm font-semibold text-gray-800 mb-5"></p>

            <form id="form-delete-layup"
                  method="POST"
                  action="">

                @csrf
                @method('DELETE')

                <div class="flex gap-2">

                    <button type="button"
                            onclick="closeModal('modal-delete-layup')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                    <button type="submit"
                            class="flex-1 bg-red-600 text-white py-2.5 rounded-lg hover:bg-red-700 text-sm font-medium transition">
                        Delete
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function openEditSupplierModal(name) {

            document.getElementById('edit-supplier-name').value = name;

            openModal('modal-edit-supplier');
        }

        function openEditLayupModal(id, name) {

            document.getElementById('edit-layup-name').value = name;

            document.getElementById('form-edit-layup').action =
                '/suppliers/{{ $supplier->id }}/layups/' + id;

            openModal('modal-edit-layup');
        }

        function openDeleteLayupModal(id, name) {

            document.getElementById('delete-layup-name').textContent =
                '"' + name + '"';

            document.getElementById('form-delete-layup').action =
                '/suppliers/{{ $supplier->id }}/layups/' + id;

            openModal('modal-delete-layup');
        }

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                [
                    'modal-edit-supplier',
                    'modal-create-layup',
                    'modal-edit-layup',
                    'modal-delete-layup'
                ].forEach(closeModal);

            }

        });

    </script>

</x-app-layout>

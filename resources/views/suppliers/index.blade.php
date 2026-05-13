<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Suppliers
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
        <div class="mb-5 flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">

            <svg class="w-4 h-4 shrink-0"
                 fill="currentColor"
                 viewBox="0 0 20 20">

                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>

            </svg>

            {{ session('success') }}

        </div>
        @endif

        <div class="flex justify-between items-center mb-5">

            <p class="text-sm text-gray-500">
                Manage your suppliers and their CLT layup data.
            </p>

            <div class="flex gap-2">

                <button onclick="openModal('modal-import')"
                   class="inline-flex items-center gap-2 border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>

                    </svg>

                    Import

                </button>

                <button onclick="openModal('modal-create')"
                   class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v16m8-8H4"/>

                    </svg>

                    Add Supplier

                </button>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider">

                    <tr>
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Layups</th>
                        <th class="px-6 py-3 text-left">Actions</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-50">

                    @forelse($suppliers as $supplier)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-6 py-4 text-gray-400">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $supplier->name }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">

                                {{ $supplier->layups_count }} layups

                            </span>

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <a href="{{ route('suppliers.show', $supplier) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    View
                                </a>

                                <button onclick="openEditModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')"
                                        class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">
                                    Edit
                                </button>

                                <a href="{{ route('suppliers.export', $supplier) }}"
                                   class="text-green-600 hover:text-green-800 text-xs font-medium">
                                    Export
                                </a>

                                <button onclick="openDeleteModal({{ $supplier->id }}, '{{ addslashes($supplier->name) }}')"
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
                                No suppliers yet.
                            </div>

                            <button onclick="openModal('modal-create')"
                               class="mt-2 inline-block text-green-600 hover:underline text-sm">

                                + Add your first supplier

                            </button>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $suppliers->links() }}
        </div>

    </div>

    {{-- ===== MODAL CREATE ===== --}}
    <div id="modal-create"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-create')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Add New Supplier
                </h3>

                <button onclick="closeModal('modal-create')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

            <form method="POST"
                  action="{{ route('suppliers.store') }}">

                @csrf

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Supplier Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="e.g. Nordic Structures Inc."
                           autofocus
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1.5">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="flex gap-2 pt-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">

                        Save Supplier

                    </button>

                    <button type="button"
                            onclick="closeModal('modal-create')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">

                        Cancel

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div id="modal-edit"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-edit')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Supplier
                </h3>

                <button onclick="closeModal('modal-edit')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

            <form id="form-edit"
                  method="POST"
                  action="">

                @csrf
                @method('PUT')

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Supplier Name
                    </label>

                    <input type="text"
                           id="edit-name"
                           name="name"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                </div>

                <div class="flex gap-2 pt-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">

                        Update

                    </button>

                    <button type="button"
                            onclick="closeModal('modal-edit')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">

                        Cancel

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- ===== MODAL DELETE ===== --}}
    <div id="modal-delete"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-delete')"></div>

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
                Delete Supplier
            </h3>

            <p class="text-sm text-gray-500 mb-1">
                Are you sure you want to delete
            </p>

            <p id="delete-name"
               class="text-sm font-semibold text-gray-800 mb-5"></p>

            <p class="text-xs text-red-500 mb-5">
                This will also delete all layups and layers under this supplier.
            </p>

            <form id="form-delete"
                  method="POST"
                  action="">

                @csrf
                @method('DELETE')

                <div class="flex gap-2">

                    <button type="button"
                            onclick="closeModal('modal-delete')"
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

    {{-- ===== MODAL IMPORT ===== --}}
    <div id="modal-import"
         class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-import')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl p-6">

            <div class="flex justify-between items-center mb-5">

                <div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Import Supplier Data
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Upload JSON layup data into a supplier.
                    </p>

                </div>

                <button onclick="closeModal('modal-import')"
                        class="text-gray-400 hover:text-gray-600">

                    <svg class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>

                    </svg>

                </button>

            </div>

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST"
                  action="{{ route('suppliers.import') }}"
                  enctype="multipart/form-data">

                @csrf

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Select Supplier
                    </label>

                    <select name="supplier_id"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">

                        <option value="">
                            -- Choose Supplier --
                        </option>

                        @foreach($suppliers as $supplier)

                            <option value="{{ $supplier->id }}">
                                {{ $supplier->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1.5">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        JSON File
                    </label>

                    <input type="file"
                           name="file"
                           accept=".json"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm">

                    @error('file')
                        <p class="text-red-500 text-xs mt-1.5">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-700 mb-5">

                    <p class="font-semibold mb-2">
                        Expected JSON format
                    </p>

<pre class="text-xs overflow-auto">{
  "supplier": {
    "name": "Supplier Name"
  },
  "layups": [
    {
      "name": "Layup A",
      "layers": [
        {
          "layer_order": 1,
          "thickness": 20.5,
          "width": 150.0,
          "angle": 0
        }
      ]
    }
  ]
}</pre>

                </div>

                <div class="flex gap-2">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">

                        Import Data

                    </button>

                    <button type="button"
                            onclick="closeModal('modal-import')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">

                        Cancel

                    </button>

                </div>

            </form>

        </div>

    </div>

    {{-- AUTO OPEN ERROR MODAL --}}
    @if($errors->any())
    <script>

        document.addEventListener('DOMContentLoaded', function() {

            @if($errors->has('file') || $errors->has('supplier_id'))

                openModal('modal-import');

            @else

                openModal('modal-create');

            @endif

        });

    </script>
    @endif

    {{-- SCRIPT --}}
    <script>

        function openModal(id) {

            document.getElementById(id).classList.remove('hidden');

            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {

            document.getElementById(id).classList.add('hidden');

            document.body.style.overflow = '';
        }

        function openEditModal(id, name) {

            document.getElementById('edit-name').value = name;

            document.getElementById('form-edit').action =
                '/suppliers/' + id;

            openModal('modal-edit');
        }

        function openDeleteModal(id, name) {

            document.getElementById('delete-name').textContent =
                '"' + name + '"';

            document.getElementById('form-delete').action =
                '/suppliers/' + id;

            openModal('modal-delete');
        }

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                [
                    'modal-create',
                    'modal-edit',
                    'modal-delete',
                    'modal-import'
                ].forEach(closeModal);

            }

        });

    </script>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('suppliers.index') }}" class="hover:text-gray-700">Suppliers</a>

            <span>/</span>

            <a href="{{ route('suppliers.show', $supplier) }}"
               class="hover:text-gray-700">
                {{ $supplier->name }}
            </a>

            <span>/</span>

            <span class="text-gray-800 font-medium">
                {{ $layup->name }}
            </span>
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

        <div class="mt-4">

            <a href="{{ route('suppliers.show', $supplier) }}"
               class="text-sm text-gray-500 hover:text-gray-700">

                ← Back to {{ $supplier->name }}

            </a>

        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6 flex justify-between items-center">

            <div>

                <h1 class="text-xl font-bold text-gray-800">
                    {{ $layup->name }}
                </h1>

                <p class="text-sm text-gray-400 mt-1">
                    {{ $layup->layers->count() }} layer(s)
                    · Supplier: {{ $supplier->name }}
                </p>

            </div>

            <div class="flex gap-2">

                <button
                    onclick="openEditLayupModal('{{ addslashes($layup->name) }}')"
                    class="border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm font-medium transition">
                    Edit
                </button>

                <button
                    onclick="openModal('modal-create-layer')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                    + Add Layer
                </button>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-700">
                    CLT Layers
                </h3>
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

                        <td class="px-6 py-4 text-gray-700">
                            {{ $layer->thickness }} mm
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $layer->width }} mm
                        </td>

                        <td class="px-6 py-4 text-gray-700">
                            {{ $layer->angle }}°
                        </td>

                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                <button
                                    onclick="openEditLayerModal(
                                        {{ $layer->id }},
                                        '{{ $layer->layer_order }}',
                                        '{{ $layer->thickness }}',
                                        '{{ $layer->width }}',
                                        '{{ $layer->angle }}'
                                    )"
                                    class="text-yellow-600 hover:text-yellow-800 text-xs font-medium">
                                    Edit
                                </button>

                                <button
                                    onclick="openDeleteLayerModal(
                                        {{ $layer->id }},
                                        '{{ $layer->layer_order }}'
                                    )"
                                    class="text-red-500 hover:text-red-700 text-xs font-medium">
                                    Delete
                                </button>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="px-6 py-12 text-center">

                            <div class="text-gray-400 text-sm">
                                No layers yet.
                            </div>

                            <button
                                onclick="openModal('modal-create-layer')"
                                class="mt-2 inline-block text-green-600 hover:underline text-sm">
                                + Add first layer
                            </button>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

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
                  action="{{ route('suppliers.layups.update', [$supplier, $layup]) }}">

                @csrf
                @method('PUT')

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Layup Name
                    </label>

                    <input type="text"
                           id="edit-layup-name"
                           name="name"
                           value="{{ old('name', $layup->name) }}"
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

    {{-- ======================= --}}
    {{-- MODAL CREATE LAYER --}}
    {{-- ======================= --}}

    <div id="modal-create-layer"
         class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-create-layer')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Add New Layer
                </h3>

                <button onclick="closeModal('modal-create-layer')"
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
                  action="{{ route('suppliers.layups.layers.store', [$supplier, $layup]) }}">

                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Layer Order
                        </label>

                        <input type="number"
                               name="layer_order"
                               value="{{ old('layer_order') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Thickness
                        </label>

                        <input type="number"
                               step="0.01"
                               name="thickness"
                               value="{{ old('thickness') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Width
                        </label>

                        <input type="number"
                               step="0.01"
                               name="width"
                               value="{{ old('width') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Angle (°)
                        </label>

                        <input type="number"
                               step="0.01"
                               name="angle"
                               value="{{ old('angle') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                </div>

                <div class="flex gap-2 pt-5">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Save
                    </button>

                    <button type="button"
                            onclick="closeModal('modal-create-layer')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>

    <div id="modal-edit-layer"
         class="hidden fixed inset-0 z-50 flex items-center justify-center overflow-y-auto p-4">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-edit-layer')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">

            <div class="flex justify-between items-center mb-5">

                <h3 class="text-lg font-semibold text-gray-800">
                    Edit Layer
                </h3>

                <button onclick="closeModal('modal-edit-layer')"
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

            <form id="form-edit-layer"
                  method="POST"
                  action="">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Layer Order
                        </label>

                        <input type="number"
                               id="edit-layer-order"
                               name="layer_order"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Thickness
                        </label>

                        <input type="number"
                               step="0.01"
                               id="edit-layer-thickness"
                               name="thickness"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Width
                        </label>

                        <input type="number"
                               step="0.01"
                               id="edit-layer-width"
                               name="width"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Angle (°)
                        </label>

                        <input type="number"
                               step="0.01"
                               id="edit-layer-angle"
                               name="angle"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                </div>

                <div class="flex gap-2 pt-5">

                    <button type="submit"
                            class="flex-1 bg-green-600 text-white py-2.5 rounded-lg hover:bg-green-700 text-sm font-medium transition">
                        Update
                    </button>

                    <button type="button"
                            onclick="closeModal('modal-edit-layer')"
                            class="flex-1 bg-gray-100 text-gray-700 py-2.5 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                        Cancel
                    </button>

                </div>

            </form>

        </div>

    </div>


    <div id="modal-delete-layer"
         class="hidden fixed inset-0 z-50 flex items-center justify-center">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"
             onclick="closeModal('modal-delete-layer')"></div>

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
                Delete Layer
            </h3>

            <p class="text-sm text-gray-500 mb-1">
                Are you sure you want to delete layer
            </p>

            <p id="delete-layer-name"
               class="text-sm font-semibold text-gray-800 mb-5"></p>

            <form id="form-delete-layer"
                  method="POST"
                  action="">

                @csrf
                @method('DELETE')

                <div class="flex gap-2">

                    <button type="button"
                            onclick="closeModal('modal-delete-layer')"
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

        function openEditLayupModal(name) {

            document.getElementById('edit-layup-name').value = name;

            openModal('modal-edit-layup');
        }

        function openEditLayerModal(id, order, thickness, width, angle) {

            document.getElementById('edit-layer-order').value = order;
            document.getElementById('edit-layer-thickness').value = thickness;
            document.getElementById('edit-layer-width').value = width;
            document.getElementById('edit-layer-angle').value = angle;

            document.getElementById('form-edit-layer').action =
                '/suppliers/{{ $supplier->id }}/layups/{{ $layup->id }}/layers/' + id;

            openModal('modal-edit-layer');
        }

        function openDeleteLayerModal(id, order) {

            document.getElementById('delete-layer-name').textContent =
                '#' + order;

            document.getElementById('form-delete-layer').action =
                '/suppliers/{{ $supplier->id }}/layups/{{ $layup->id }}/layers/' + id;

            openModal('modal-delete-layer');
        }

        document.addEventListener('keydown', function(e) {

            if (e.key === 'Escape') {

                [
                    'modal-edit-layup',
                    'modal-create-layer',
                    'modal-edit-layer',
                    'modal-delete-layer'
                ].forEach(closeModal);

            }

        });

    </script>

</x-app-layout>

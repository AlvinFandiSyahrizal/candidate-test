<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Import Supplier Data</h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded p-6">
            <form method="POST" action="{{ route('suppliers.import') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Select Supplier
                    </label>
                    <select name="supplier_id"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        JSON File
                    </label>
                    <input type="file" name="file" accept=".json"
                           class="w-full border rounded px-3 py-2">
                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Import
                    </button>
                    <a href="{{ route('suppliers.index') }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <div class="mt-4 bg-blue-50 border border-blue-200 rounded p-4 text-sm text-blue-700">
            <p class="font-medium mb-1">Expected JSON format:</p>
            <pre class="text-xs overflow-auto">{
  "supplier": { "name": "Supplier Name" },
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
    </div>
</x-app-layout>

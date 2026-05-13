<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Conflict Resolution — {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">

        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-300 rounded text-yellow-800 text-sm">
            <strong>{{ count($conflicts) }} conflict(s) found.</strong>
            For each conflict, choose which version to keep.
        </div>

        <form method="POST" action="{{ route('suppliers.import.resolve') }}">
            @csrf

            @foreach($conflicts as $index => $conflict)
            @php
                $key = $conflict['layup_name'] . '_' . $conflict['layer_order'];
            @endphp

            <div class="bg-white shadow rounded mb-4 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                    <span class="font-medium text-sm">
                        Conflict {{ $index + 1 }} of {{ count($conflicts) }}
                    </span>
                    <span class="text-gray-500 text-sm">
                        Layup: <strong>{{ $conflict['layup_name'] }}</strong>
                        — Layer Order: <strong>{{ $conflict['layer_order'] }}</strong>
                    </span>
                </div>

                <div class="grid grid-cols-2 divide-x">
                    {{-- Existing --}}
                    <div class="p-4">
                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                            <input type="radio"
                                   name="resolutions[{{ $key }}]"
                                   value="keep"
                                   checked
                                   class="text-green-600">
                            <span class="font-medium text-green-700">
                                ✅ Keep Existing
                            </span>
                        </label>
                        <table class="text-sm w-full">
                            <tr class="border-b">
                                <td class="py-1 text-gray-500 w-24">Thickness</td>
                                <td class="py-1 font-medium">{{ $conflict['existing']['thickness'] }}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-1 text-gray-500">Width</td>
                                <td class="py-1 font-medium">{{ $conflict['existing']['width'] }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">Angle</td>
                                <td class="py-1 font-medium">{{ $conflict['existing']['angle'] }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Incoming --}}
                    <div class="p-4">
                        <label class="flex items-center gap-2 mb-3 cursor-pointer">
                            <input type="radio"
                                   name="resolutions[{{ $key }}]"
                                   value="incoming"
                                   class="text-blue-600">
                            <span class="font-medium text-blue-700">
                                ✅ Accept Incoming
                            </span>
                        </label>
                        <table class="text-sm w-full">
                            <tr class="border-b">
                                <td class="py-1 text-gray-500 w-24">Thickness</td>
                                <td class="py-1 font-medium
                                    {{ $conflict['existing']['thickness'] != $conflict['incoming']['thickness'] ? 'text-red-600' : '' }}">
                                    {{ $conflict['incoming']['thickness'] }}
                                    @if($conflict['existing']['thickness'] != $conflict['incoming']['thickness'])
                                        <span class="text-xs text-red-400">(changed)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-1 text-gray-500">Width</td>
                                <td class="py-1 font-medium
                                    {{ $conflict['existing']['width'] != $conflict['incoming']['width'] ? 'text-red-600' : '' }}">
                                    {{ $conflict['incoming']['width'] }}
                                    @if($conflict['existing']['width'] != $conflict['incoming']['width'])
                                        <span class="text-xs text-red-400">(changed)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-1 text-gray-500">Angle</td>
                                <td class="py-1 font-medium
                                    {{ $conflict['existing']['angle'] != $conflict['incoming']['angle'] ? 'text-red-600' : '' }}">
                                    {{ $conflict['incoming']['angle'] }}
                                    @if($conflict['existing']['angle'] != $conflict['incoming']['angle'])
                                        <span class="text-xs text-red-400">(changed)</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="flex gap-3 mt-6">
                <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
                    Apply Resolution
                </button>
                <a href="{{ route('suppliers.import.form') }}"
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Cancel Import
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

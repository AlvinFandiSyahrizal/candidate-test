<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use App\Http\Requests\StoreCltLayerRequest;

class CltLayerController extends Controller
{
    public function create(Supplier $supplier, CltLayup $layup)
    {
        return view('layers.create', compact('supplier', 'layup'));
    }

    public function store(StoreCltLayerRequest $request, Supplier $supplier, CltLayup $layup)
    {
        $layup->layers()->create($request->validated());
        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer created successfully.');
    }

    public function edit(Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        return view('layers.edit', compact('supplier', 'layup', 'layer'));
    }

    public function update(StoreCltLayerRequest $request, Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        $layer->update($request->validated());
        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer updated successfully.');
    }

    public function destroy(Supplier $supplier, CltLayup $layup, CltLayer $layer)
    {
        $layer->delete();
        return redirect()->route('suppliers.layups.show', [$supplier, $layup])
            ->with('success', 'Layer deleted successfully.');
    }
}

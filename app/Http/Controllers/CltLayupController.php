<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Http\Requests\StoreCltLayupRequest;

class CltLayupController extends Controller
{
    public function index(Supplier $supplier)
    {
        $layups = $supplier->layups()->withCount('layers')->latest()->paginate(10);
        return view('layups.index', compact('supplier', 'layups'));
    }

    public function create(Supplier $supplier)
    {
        return view('layups.create', compact('supplier'));
    }

    public function store(StoreCltLayupRequest $request, Supplier $supplier)
    {
        $supplier->layups()->create($request->validated());
        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup created successfully.');
    }

    public function show(Supplier $supplier, CltLayup $layup)
    {
        $layup->load('layers');
        return view('layups.show', compact('supplier', 'layup'));
    }

    public function edit(Supplier $supplier, CltLayup $layup)
    {
        return view('layups.edit', compact('supplier', 'layup'));
    }

    public function update(StoreCltLayupRequest $request, Supplier $supplier, CltLayup $layup)
    {
        $layup->update($request->validated());
        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup updated successfully.');
    }

    public function destroy(Supplier $supplier, CltLayup $layup)
    {
        $layup->delete();
        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'Layup deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierRepositoryInterface $supplierRepo
    ) {}

    public function index()
    {
        $suppliers = $this->supplierRepo->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->supplierRepo->create($request->validated());
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('layups.layers');
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $this->supplierRepo->update($supplier, $request->validated());
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierRepo->delete($supplier);
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}

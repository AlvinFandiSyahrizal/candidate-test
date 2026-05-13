<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Supplier::withCount('layups')->latest()->paginate($perPage);
    }

    public function findById(int $id): Supplier
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(Supplier $supplier, array $data): Supplier
    {
        $supplier->update($data);
        return $supplier;
    }

    public function delete(Supplier $supplier): void
    {
        $supplier->delete();
    }
}

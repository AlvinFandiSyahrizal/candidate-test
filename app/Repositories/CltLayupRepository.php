<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Repositories\Contracts\CltLayupRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class CltLayupRepository implements CltLayupRepositoryInterface
{
    public function paginateBySupplier(Supplier $supplier, int $perPage = 10): LengthAwarePaginator
    {
        return $supplier->layups()->withCount('layers')->latest()->paginate($perPage);
    }

    public function findById(int $id): CltLayup
    {
        return CltLayup::findOrFail($id);
    }

    public function create(Supplier $supplier, array $data): CltLayup
    {
        return $supplier->layups()->create($data);
    }

    public function update(CltLayup $layup, array $data): CltLayup
    {
        $layup->update($data);
        return $layup;
    }

    public function delete(CltLayup $layup): void
    {
        $layup->delete();
    }
}

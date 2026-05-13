<?php

namespace App\Repositories\Contracts;

use App\Models\Supplier;
use App\Models\CltLayup;
use Illuminate\Pagination\LengthAwarePaginator;

interface CltLayupRepositoryInterface
{
    public function paginateBySupplier(Supplier $supplier, int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): CltLayup;
    public function create(Supplier $supplier, array $data): CltLayup;
    public function update(CltLayup $layup, array $data): CltLayup;
    public function delete(CltLayup $layup): void;
}

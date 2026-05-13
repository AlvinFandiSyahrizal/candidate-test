<?php

namespace App\Repositories\Contracts;

use App\Models\CltLayup;
use App\Models\CltLayer;

interface CltLayerRepositoryInterface
{
    public function create(CltLayup $layup, array $data): CltLayer;
    public function update(CltLayer $layer, array $data): CltLayer;
    public function delete(CltLayer $layer): void;
}


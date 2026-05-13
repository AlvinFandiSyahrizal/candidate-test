<?php

namespace App\Repositories;

use App\Models\CltLayup;
use App\Models\CltLayer;
use App\Repositories\Contracts\CltLayerRepositoryInterface;

class CltLayerRepository implements CltLayerRepositoryInterface
{
    public function create(CltLayup $layup, array $data): CltLayer
    {
        return $layup->layers()->create($data);
    }

    public function update(CltLayer $layer, array $data): CltLayer
    {
        $layer->update($data);
        return $layer;
    }

    public function delete(CltLayer $layer): void
    {
        $layer->delete();
    }
}

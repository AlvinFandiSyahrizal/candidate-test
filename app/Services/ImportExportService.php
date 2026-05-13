<?php

namespace App\Services;

use App\Models\Supplier;
use App\Services\Contracts\ImportExportServiceInterface;

class ImportExportService implements ImportExportServiceInterface
{
    public function export(Supplier $supplier): array
    {
        return [
            'supplier' => [
                'id'   => $supplier->id,
                'name' => $supplier->name,
            ],
            'layups' => $supplier->layups->map(function ($layup) {
                return [
                    'id'     => $layup->id,
                    'name'   => $layup->name,
                    'layers' => $layup->layers->map(function ($layer) {
                        return [
                            'layer_order' => $layer->layer_order,
                            'thickness'   => $layer->thickness,
                            'width'       => $layer->width,
                            'angle'       => $layer->angle,
                        ];
                    }),
                ];
            })->toArray(),
        ];
    }

    public function detectConflicts(Supplier $supplier, array $layups): array
    {
        $conflicts = [];

        foreach ($layups as $layupData) {
            $existingLayup = $supplier->layups()
                ->where('name', $layupData['name'])
                ->first();

            if (!$existingLayup) continue;

            foreach ($layupData['layers'] as $layerData) {
                $existingLayer = $existingLayup->layers()
                    ->where('layer_order', $layerData['layer_order'])
                    ->first();

                if (!$existingLayer) continue;

                $isDifferent =
                    $existingLayer->thickness != $layerData['thickness'] ||
                    $existingLayer->width     != $layerData['width']     ||
                    $existingLayer->angle     != $layerData['angle'];

                if ($isDifferent) {
                    $conflicts[] = [
                        'layup_name'  => $layupData['name'],
                        'layer_order' => $layerData['layer_order'],
                        'existing'    => [
                            'thickness' => $existingLayer->thickness,
                            'width'     => $existingLayer->width,
                            'angle'     => $existingLayer->angle,
                        ],
                        'incoming'    => [
                            'thickness' => $layerData['thickness'],
                            'width'     => $layerData['width'],
                            'angle'     => $layerData['angle'],
                        ],
                    ];
                }
            }
        }

        return $conflicts;
    }

    public function processImport(Supplier $supplier, array $layups, string $strategy): void
    {
        foreach ($layups as $layupData) {
            $layup = $supplier->layups()->firstOrCreate(
                ['name' => $layupData['name']]
            );

            foreach ($layupData['layers'] as $layerData) {
                $existingLayer = $layup->layers()
                    ->where('layer_order', $layerData['layer_order'])
                    ->first();

                if ($existingLayer) {
                    if ($strategy === 'overwrite') {
                        $existingLayer->update([
                            'thickness' => $layerData['thickness'],
                            'width'     => $layerData['width'],
                            'angle'     => $layerData['angle'],
                        ]);
                    }
                } else {
                    $layup->layers()->create($layerData);
                }
            }
        }
    }

    public function resolveConflicts(Supplier $supplier, array $layups, array $resolutions): void
    {
        foreach ($layups as $layupData) {
            $layup = $supplier->layups()->firstOrCreate(
                ['name' => $layupData['name']]
            );

            foreach ($layupData['layers'] as $layerData) {
                $existingLayer = $layup->layers()
                    ->where('layer_order', $layerData['layer_order'])
                    ->first();

                if ($existingLayer) {
                    $key        = $layupData['name'] . '_' . $layerData['layer_order'];
                    $resolution = $resolutions[$key] ?? 'keep';

                    if ($resolution === 'incoming') {
                        $existingLayer->update([
                            'thickness' => $layerData['thickness'],
                            'width'     => $layerData['width'],
                            'angle'     => $layerData['angle'],
                        ]);
                    }
                } else {
                    $layup->layers()->create($layerData);
                }
            }
        }
    }
}


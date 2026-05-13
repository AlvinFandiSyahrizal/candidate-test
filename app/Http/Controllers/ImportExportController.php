<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Http\Request;

class ImportExportController extends Controller
{
    /**
     * Export supplier + layups + layers as JSON
     */
    public function export(Supplier $supplier)
    {
        $data = [
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
            }),
        ];

        $filename = 'supplier-' . str()->slug($supplier->name) . '-' . now()->format('Ymd') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        $suppliers = Supplier::all();
        return view('suppliers.import', compact('suppliers'));
    }

    /**
     * Handle import
     */
    public function import(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'file'        => 'required|file|mimes:json|max:2048',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        $content  = file_get_contents($request->file('file')->getRealPath());
        $data     = json_decode($content, true);

        if (!$data || !isset($data['layups'])) {
            return back()->with('error', 'Invalid JSON format.');
        }

        $conflicts = [];

        foreach ($data['layups'] as $layupData) {
            $existingLayup = $supplier->layups()->where('name', $layupData['name'])->first();

            if ($existingLayup) {
                foreach ($layupData['layers'] as $layerData) {
                    $existingLayer = $existingLayup->layers()
                        ->where('layer_order', $layerData['layer_order'])
                        ->first();

                    if ($existingLayer) {
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
                                'incoming' => [
                                    'thickness' => $layerData['thickness'],
                                    'width'     => $layerData['width'],
                                    'angle'     => $layerData['angle'],
                                ],
                            ];
                        }
                    }
                }
            }
        }

        if (!empty($conflicts)) {
            session([
                'import_conflicts'    => $conflicts,
                'import_data'         => $data,
                'import_supplier_id'  => $supplier->id,
            ]);
            return redirect()->route('suppliers.import.conflicts');
        }

        $this->processImport($supplier, $data['layups'], 'overwrite');

        return redirect()->route('suppliers.index')
            ->with('success', 'Import successful!');
    }

    /**
     * Process the actual import
     */
    public function processImport(Supplier $supplier, array $layups, string $strategy)
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

        /**
     * Show conflict resolution page
     */
    public function showConflicts()
    {
        $conflicts   = session('import_conflicts');
        $importData  = session('import_data');
        $supplierId  = session('import_supplier_id');

        if (!$conflicts || !$importData) {
            return redirect()->route('suppliers.import.form')
                ->with('error', 'No pending import found.');
        }

        $supplier = Supplier::findOrFail($supplierId);

        return view('suppliers.conflicts', compact('conflicts', 'importData', 'supplier'));
    }

    /**
     * Resolve conflicts and process import
     */
    public function resolveConflicts(Request $request)
    {
        $importData = session('import_data');
        $supplierId = session('import_supplier_id');

        if (!$importData || !$supplierId) {
            return redirect()->route('suppliers.import.form')
                ->with('error', 'No pending import found.');
        }

        $supplier   = Supplier::findOrFail($supplierId);
        $resolutions = $request->input('resolutions', []);

        foreach ($importData['layups'] as $layupData) {
            $layup = $supplier->layups()->firstOrCreate(
                ['name' => $layupData['name']]
            );

            foreach ($layupData['layers'] as $layerData) {
                $existingLayer = $layup->layers()
                    ->where('layer_order', $layerData['layer_order'])
                    ->first();

                if ($existingLayer) {
                    // Buat key unik untuk tiap konflik
                    $key = $layupData['name'] . '_' . $layerData['layer_order'];
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

        session()->forget(['import_conflicts', 'import_data', 'import_supplier_id']);

        return redirect()->route('suppliers.index')
            ->with('success', 'Import completed with conflict resolution!');
    }
}



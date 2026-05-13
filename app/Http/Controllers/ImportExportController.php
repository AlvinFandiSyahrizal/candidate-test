<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Services\Contracts\ImportExportServiceInterface;

class ImportExportController extends Controller
{
    public function __construct(
        protected ImportExportServiceInterface $importExportService
    ) {}

    public function export(Supplier $supplier)
    {
        $data     = $this->importExportService->export($supplier);
        $filename = 'supplier-' . str()->slug($supplier->name) . '-' . now()->format('Ymd') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function importForm()
    {
        $suppliers = Supplier::all();
        return view('suppliers.import', compact('suppliers'));
    }

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

        $conflicts = $this->importExportService->detectConflicts($supplier, $data['layups']);

        if (!empty($conflicts)) {
            session([
                'import_conflicts'   => $conflicts,
                'import_data'        => $data,
                'import_supplier_id' => $supplier->id,
            ]);
            return redirect()->route('suppliers.import.conflicts');
        }

        $this->importExportService->processImport($supplier, $data['layups'], 'overwrite');

        return redirect()->route('suppliers.index')
            ->with('success', 'Import successful!');
    }

    public function showConflicts()
    {
        $conflicts  = session('import_conflicts');
        $importData = session('import_data');
        $supplierId = session('import_supplier_id');

        if (!$conflicts || !$importData) {
            return redirect()->route('suppliers.import.form')
                ->with('error', 'No pending import found.');
        }

        $supplier = Supplier::findOrFail($supplierId);
        return view('suppliers.conflicts', compact('conflicts', 'importData', 'supplier'));
    }

    public function resolveConflicts(Request $request)
    {
        $importData  = session('import_data');
        $supplierId  = session('import_supplier_id');

        if (!$importData || !$supplierId) {
            return redirect()->route('suppliers.import.form')
                ->with('error', 'No pending import found.');
        }

        $supplier    = Supplier::findOrFail($supplierId);
        $resolutions = $request->input('resolutions', []);

        $this->importExportService->resolveConflicts($supplier, $importData['layups'], $resolutions);

        session()->forget(['import_conflicts', 'import_data', 'import_supplier_id']);

        return redirect()->route('suppliers.index')
            ->with('success', 'Import completed with conflict resolution!');
    }
}

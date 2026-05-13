<?php

namespace App\Services\Contracts;

use App\Models\Supplier;

interface ImportExportServiceInterface
{
    public function export(Supplier $supplier): array;
    public function detectConflicts(Supplier $supplier, array $layups): array;
    public function processImport(Supplier $supplier, array $layups, string $strategy): void;
    public function resolveConflicts(Supplier $supplier, array $layups, array $resolutions): void;
}

<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CltLayupController;
use App\Http\Controllers\CltLayerController;
use App\Http\Controllers\ImportExportController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('suppliers/import', [ImportExportController::class, 'importForm'])
        ->name('suppliers.import.form');
    Route::post('suppliers/import', [ImportExportController::class, 'import'])
        ->name('suppliers.import');
    Route::get('suppliers/{supplier}/export', [ImportExportController::class, 'export'])
        ->name('suppliers.export');

    Route::get('suppliers/import/conflicts', [ImportExportController::class, 'showConflicts'])
    ->name('suppliers.import.conflicts');
    Route::post('suppliers/import/resolve', [ImportExportController::class, 'resolveConflicts'])
    ->name('suppliers.import.resolve');

    Route::resource('suppliers', SupplierController::class);
    Route::resource('suppliers.layups', CltLayupController::class);
    Route::resource('suppliers.layups.layers', CltLayerController::class);


});

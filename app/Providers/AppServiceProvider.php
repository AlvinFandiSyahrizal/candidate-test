<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\Contracts\CltLayupRepositoryInterface;
use App\Repositories\Contracts\CltLayerRepositoryInterface;
use App\Services\Contracts\ImportExportServiceInterface;
use App\Repositories\SupplierRepository;
use App\Repositories\CltLayupRepository;
use App\Repositories\CltLayerRepository;
use App\Services\ImportExportService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(CltLayupRepositoryInterface::class, CltLayupRepository::class);
        $this->app->bind(CltLayerRepositoryInterface::class, CltLayerRepository::class);
        $this->app->bind(ImportExportServiceInterface::class, ImportExportService::class);
    }

    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use App\Http\Controllers\ClientOrderController;
use App\Interfaces\CrudRepoInterface;
use App\Repository\ClientOrderRepo;
use Illuminate\Support\ServiceProvider;

class CrudRepoProviders extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->when(ClientOrderController::class)
            ->needs(CrudRepoInterface::class)
            ->give(function () {
               return new ClientOrderRepo();
            });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

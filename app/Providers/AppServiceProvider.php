<?php

namespace App\Providers;

use App\Repositories\Contracts\ProyectoRepositoryInterface;
use App\Repositories\Contracts\TareaRepositoryInterface;
use App\Repositories\ProyectoRepository;
use App\Repositories\TareaRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProyectoRepositoryInterface::class, ProyectoRepository::class);
        $this->app->bind(TareaRepositoryInterface::class, TareaRepository::class);
    }

    public function boot(): void
    {
        //
    }
}

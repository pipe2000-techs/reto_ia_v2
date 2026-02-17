<?php

namespace App\Providers;

use App\Repositories\Contracts\ProyectoRepositoryInterface;
use App\Repositories\Contracts\TareaRepositoryInterface;
use App\Repositories\Contracts\SubtareaRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\ProyectoRepository;
use App\Repositories\TareaRepository;
use App\Repositories\SubtareaRepository;
use App\Repositories\TagRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProyectoRepositoryInterface::class, ProyectoRepository::class);
        $this->app->bind(TareaRepositoryInterface::class, TareaRepository::class);
        $this->app->bind(SubtareaRepositoryInterface::class, SubtareaRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
    }

    public function boot(): void
    {
        //
    }
}

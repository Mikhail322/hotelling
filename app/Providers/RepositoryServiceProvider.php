<?php

namespace App\Providers;

use App\Interfaces\Repositories\BookingRepositoryInterface;
use App\Interfaces\Repositories\CapacityRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\CapacityRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(CapacityRepositoryInterface::class, CapacityRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

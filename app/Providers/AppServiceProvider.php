<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Review;
use App\Observers\OrderObserver;
use App\Observers\RestaurantObserver;
use App\Observers\ReviewObserver;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\AddressRepositoryInterface;
use App\Repositories\Contracts\AdminRestaurantRepositoryInterface;
use App\Repositories\Contracts\MenuSectionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\AddressRepository;
use App\Repositories\Eloquent\AdminRestaurantRepository;
use App\Repositories\Eloquent\MenuSectionRepository;
use App\Repositories\Eloquent\UserRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AddressRepositoryInterface::class,
            AddressRepository::class,
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            AdminRestaurantRepositoryInterface::class,
            AdminRestaurantRepository::class
        );
        $this->app->bind(
            MenuSectionRepositoryInterface::class,
            MenuSectionRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Restaurant::observe(RestaurantObserver::class);
        Order::observe(OrderObserver::class);
        Review::observe(ReviewObserver::class);
    }
}

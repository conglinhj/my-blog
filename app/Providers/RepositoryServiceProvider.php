<?php

namespace App\Providers;

use App\Repositories\Eloquent\ArticleRepositoryEloquent;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ArticleRepositoryInterface::class, ArticleRepositoryEloquent::class);
    }
}

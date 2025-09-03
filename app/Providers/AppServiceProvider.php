<?php

namespace App\Providers;

use App\Database\OdbcConnector;
use App\Models\User;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::extend('odbc', function ($config, $name) {
            $connector = new OdbcConnector();
            $pdo = $connector->connect($config);

            return new Connection($pdo, $config['database'] ?? null, $config['prefix'] ?? '', $config);
        });
        Gate::define('admin', function (User $user) {
            return $user->is_admin === 'admin';
        });

        Gate::define('ricky', function (User $user) {
            return $user->name === 'Ricky Ramadhan Arya Hussein';
        });

        Paginator::useBootstrap();
    }
}

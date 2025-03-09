<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->routes(function () {
            $this->mapWebRoutes();
            $this->mapAuthRoutes();
            $this->mapSuperAdminRoutes();
            $this->mapAdminRoutes();
            $this->mapTeacherRoutes();
            $this->mapVPAARoutes();
            $this->mapHumanResourceRoutes();
        });
    }

    /**
     * Define the "web" routes for the application.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "auth" routes for the application.
     *
     * @return void
     */
    protected function mapAuthRoutes()
    {
        Route::middleware(['web'])
             ->prefix('Authenticate')
             ->group(base_path('routes/auth.php'));
    }

    /**
     * Define the "superadmin" routes for the application.
     *
     * @return void
     */
    protected function mapSuperAdminRoutes()
    {
        Route::middleware(['web', 'auth', 'is_superadmin'])
             ->prefix('Superadmin')
             ->group(base_path('routes/superadmin.php'));
    }

    /**
     * Define the "admin" routes for the application.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::middleware(['web', 'auth', 'is_admin'])
             ->prefix('Admin')
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "teacher" routes for the application.
     *
     * @return void
     */
    protected function mapTeacherRoutes()
    {
        Route::middleware(['web', 'auth', 'is_teacher'])
             ->prefix('Teacher')
             ->group(base_path('routes/teacher.php'));
    }
    /**
     * Define the "VPAA" routes for the application.
     *
     * @return void
     */
    protected function mapVPAARoutes()
    {
        Route::middleware(['web', 'auth', 'is_vpaa'])
             ->prefix('VPAA')
             ->group(base_path('routes/vpaa.php'));
    }
    /**
     * Define the "HR" routes for the application.
     *
     * @return void
     */
    protected function mapHumanResourceRoutes()
    {
        Route::middleware(['web', 'auth', 'is_hr'])
             ->prefix('HR')
             ->group(base_path('routes/humanresource.php'));
    }
}

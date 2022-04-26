<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapFlutterRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/form.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/export.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/ktt.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/kabag.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/admin.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/logistic.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/inventory.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/stock.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/sarana.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/rental.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/absen.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/android/api.php'));
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web/hse.php'));

    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api/android.php'));
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/flutter/api.php'));
        Route::prefix('/api/v1')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/flutter/api_v1.php'));
        // Route::group([
        //      'middleware' => ['api', 'api_version:v1'],
        //      'namespace'  => "{$this->apiNamespace}\V1",
        //      'prefix'     => 'api/v1',
        //  ], function ($router) {
        //      require base_path('routes/flutter/api_v1.php');
        //  });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapFlutterRoutes()
    {
        Route::prefix('flutter')
             ->middleware('flutter')
             ->namespace($this->namespace)
             ->group(base_path('routes/flutter/api.php'));
    }
}

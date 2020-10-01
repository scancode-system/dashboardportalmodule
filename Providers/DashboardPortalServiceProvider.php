<?php

namespace Modules\DashboardPortal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use Modules\DashboardPortal\Services\Export\PortalUpdateService;
use Carbon\Carbon;

class DashboardPortalServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'DashboardPortal';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'dashboardportal';
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        if(Storage::exists('exportauto/time')){

            $auto = Storage::get('exportauto/time');
            $carbon = Carbon::createFromFormat('H:i', $auto);
            
            $this->app->booted(function () use($carbon){
                $schedule = $this->app->make(Schedule::class);
                $schedule->call(function () {


                    if(Storage::exists('token/token')){
                       $token = Storage::get('token/token');
                       $portal_update_service = new PortalUpdateService();
                       $portal_update_service->start($token);

                   }


                })->cron($carbon->minute.' '.$carbon->hour.' * * *');
               //})->cron('* * * * *');
            });
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ViewComposerServiceProvider::class);
    }


    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );

        /*$this->publishes([
            __DIR__.'/../Config/config.php' => config_path('dashboardportal.php'),
        ], 'config');*/
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);


        /*$viewPath = resource_path('views/modules/dashboardportal');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/dashboardportal';
        }, \Config::get('view.paths')), [$sourcePath]), 'dashboardportal');*/
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}

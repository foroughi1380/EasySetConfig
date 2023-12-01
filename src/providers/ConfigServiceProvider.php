<?php
namespace Gopex\EasySetConfig\providers;

use Gopex\EasySetConfig\commands\FromConfig;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        $this->publishes([
            __DIR__ . "/../configs/configSet.php" => config_path("configSet.php")
        ],["esconfig"]);
        $this->commands([
            FromConfig::class
        ]);
    }

    public function register()
    {
//        $this->app->singleton(IConfigRepository::class , function (){
//            return new EloquentConfigRepository();
//        });
//
//        $this->app->singleton("emconfing-config" , function (){
//            return new EMConfigService();
//        });
//
//        $this->app->singleton(ConfigSetService::class, function(){
//            return new ConfigSetService();
//        });
    }
}

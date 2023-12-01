<?php
namespace Gopex\EasySetConfig\providers;

use Gopex\EasySetConfig\commands\FromConfig;
use Gopex\EasySetConfig\utils\ESConfigAccess;
use Gopex\EasySetConfig\utils\ESConfigAccessWithoutCache;
use Gopex\EasySetConfig\utils\IESAccess;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../database/migrations");
        $this->publishes([
            __DIR__ . "/../configs/configSet.php" => config_path("configSet.php"),
            __DIR__ . "/../configs/easySetConfig.php" => config_path("easySetConfig.php")
        ],["esconfig"]);
        $this->commands([
            FromConfig::class
        ]);
    }

    public function register()
    {
        $this->app->singleton(IESAccess::class , function (){
            if (config("easySetConfig.cache_enabled")){
                return new ESConfigAccess("");
            }else{
                return new ESConfigAccessWithoutCache("");
            }
        });
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

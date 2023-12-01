<?php

namespace Gopex\EasySetConfig\commands;

use Gopex\EasySetConfig\utils\config\ESConfigDescription;
use Gopex\EasySetConfig\utils\config\ESConfigExtras;
use Gopex\EasySetConfig\utils\config\ESConfigProperty;
use Gopex\EasySetConfig\utils\config\ESConfigTitle;
use Gopex\EasySetConfig\utils\ESConfig;
use Illuminate\Console\Command;

class FromConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esconfig:from-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        [$scope , $keys] = $this->loadConfigFile();
        dd($scope);
    }


    function loadConfigFile($file="configSet.php"){
        $configs = require config_path($file);

        $scopes = ["" => []];
        $keys = [];
        foreach (array_keys($configs) as $key){
            $config = $configs[$key];
            if ($config instanceof ESConfigProperty){
                $keys[$key] = $config;
            }else if ($config instanceof ESConfigTitle){
                $scopes[""]["title"] = $config->title;
            }else if ($config instanceof ESConfigDescription) {
                $scopes[""]["description"] = $config->description;
            }else if ($config instanceof ESConfigExtras){
                $scopes[""]["extras"] = $config->extras;
            }else{
                $res = $this->formatConfigProperty($key , $config);
                $scopes = array_merge_recursive($scopes, $res[0]);
                $keys = array_merge($keys, $res[1]);
            }
        }

        return [$scopes , $keys];
    }

    function formatConfigProperty($scope , $configs){
        $scopes = [];
        $keys = [];

        foreach (array_keys($configs) as $key){
            $config = $configs[$key];
            if ($config instanceof ESConfigProperty){
                $keys[$scope . "." .$key] = $config;
            }else if ($config instanceof ESConfigTitle){
                $scopes[$scope]["title"] = $config->title;
            }else if ($config instanceof ESConfigDescription){
                $scopes[$scope]["description"] = $config->description;
            }else if ($config instanceof ESConfigExtras){
                $scopes[$scope]["extras"] = $config->extras;
            }else{
                if (! isset($scopes[$scope . "." .$key])) $scopes[$scope . "." .$key] = [];

                $res = $this->formatConfigProperty($scope . "." .$key , $config);
                $scopes = array_merge_recursive($scopes, $res[0]);
                $keys = array_merge($keys, $res[1]);
            }
        }


        return [$scopes , $keys];
    }
}

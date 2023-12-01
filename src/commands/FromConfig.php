<?php

namespace Gopex\EasySetConfig\commands;

use Dflydev\DotAccessData\Data;
use Gopex\EasySetConfig\database\models\Keys;
use Gopex\EasySetConfig\database\models\Scope;
use Gopex\EasySetConfig\facade\ESConfig;
use Gopex\EasySetConfig\utils\config\ESConfigDescription;
use Gopex\EasySetConfig\utils\config\ESConfigExtras;
use Gopex\EasySetConfig\utils\config\ESConfigProperty;
use Gopex\EasySetConfig\utils\config\ESConfigTitle;
use Illuminate\Console\Command;

class FromConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'esconfig:from-config {--d|no-delete : Optional , disable deleting} {--i|no-insert : Optional , disable inserting} {--u|no-update : Optional , disable updating} {--renew : remove all config in database and recreate all things}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load config/configSet.php file and sync all data to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option("renew") && $this->confirm("Are your sure to remove all scopes and keys in database?")){
            Scope::query()->truncate();
            Keys::query()->truncate();
            echo "truncate table success";
            echo ESConfig::forgetAll() ? "cache removed" : "error in remove cache";
        }

        [$cs , $ck] = $this->loadConfigFile();
        [$ds , $dk] = $this->loadFromDataBase();

        $sInsert = $this->getInsert($cs , $ds);
        $kInsert = $this->getInsert($ck , $dk);

        $sUpdate = $this->getScopeUpdate($cs , $ds);
        $kUpdate = $this->getKeysUpdates($ck , $dk);

        $sDelete = $this->getDeletes($cs , $ds);
        $kDelete = $this->getDeletes($ck , $dk);



        if ($this->option("no-insert")){
            $sInsert = "disabled(" . count($sInsert) . ")";
            $kInsert = "disabled(" . count($kInsert) . ")";
        }else{
            foreach ($sInsert as $k=>$v){ // sorry but it has cast
                Scope::query()->create([
                    "scope" => $k,
                    "title" => $v["title"] ?? null,
                    "description" => $v["description"] ?? null,
                    "extras" => $v["extras"] ?? null,
                ]);
            }
            $sInsert = count($sInsert);


            foreach ($kInsert as $k=>$v){ // sorry but it has cast
                Keys::query()->create([
                    "key" => $k,
                    "value" => $v->initValue,
                    "type" => $v->type,
                    "title" => $v->title,
                    "description" => $v->description,
                    "extras" => $v->extras,
                ]);
            }
            $kInsert = count($kInsert);
        }


        if ($this->option("no-update")){
            $sUpdate = "disabled(" . count($sUpdate) . ")";
            $kUpdate = "disabled(" . count($kUpdate) . ")";
        }else{
            foreach ($sUpdate as $k=>[$v , $model]){ // sorry but it has cast
                /** @var $model Scope */
                $model->fill($v)->save();
            }
            $sUpdate = count($sUpdate);

            foreach ($kUpdate as $k=>[$v , $model]){ // sorry but it has cast
                /** @var $model Scope */
                $model->fill($v->toArray())->save();
            }
            $kUpdate = count($kUpdate);
        }



        if ($this->option("no-delete")){
            $sDelete = "disabled(" . count($sDelete) . ")";
            $kDelete = "disabled(" . count($kDelete) . ")";
        }else{
            Scope::query()->whereIn("scope" , array_keys($sDelete))->delete();
            $sDelete = count($sDelete);

            Keys::query()->whereIn("key" , array_keys($kDelete))->delete();
            foreach (array_keys($kDelete) as $k){
                ESConfig::forget($k);
            }
            $kDelete = count($kDelete);
        }



        $this->printStatus("Scopes" , $sInsert , $sUpdate , $sDelete);
        $this->printStatus("Keys" , $kInsert , $kUpdate , $kDelete);
    }


    function getInsert($config , $db)
    {
        $insert = [];

        foreach ($config as $k=>$v){
            if (! isset($db[$k])){
                $insert[$k]=$v;
            }
        }

        return $insert;
    }
    function getDeletes($config , $db)
    {
        $del = [];

        foreach ($db as $k=>$v){
            if (! isset($config[$k])){
                $del[$k]=$v;
            }
        }

        return $del;
    }
    function getScopeUpdate($config , $db)
    {
        $update = [];

        foreach ($config as $k=>$v){
            if (isset($db[$k]) && (
                    ($v["title"]??null) != $db[$k]->title ||
                    ($v["description"]??null) != $db[$k]->description ||
                    json_encode($v["extras"]??null) != json_encode($db[$k]->extras) )){
                $update[$k]=[$v , $db[$k]];
            }
        }

        return $update;
    }

    function getKeysUpdates($config , $db)
    {
        $update = [];

        foreach ($config as $k=>$v){
            if (isset($db[$k]) && (
                    $v->title != $db[$k]->title ||
                    $v->type != $db[$k]->type ||
                    $v->description != $db[$k]->description ||
                    (json_encode($v->extras) != json_encode($db[$k]->extras)) ) ){
                $update[$k]=[$v , $db[$k]];
            }
        }

        return $update;
    }

    function printStatus($title , $insertCount , $updateCount , $deleteCount){
        echo "[--- $title ---]\n";
        echo "insert : $insertCount\n";
        echo "update : $updateCount\n";
        echo "delete : $deleteCount\n\n";
    }

    function loadFromDataBase(){
        $scope = Scope::query()->get()->mapWithKeys(function ($item , $key){
            return [$item->scope => $item];
        });
        $keys = Keys::query()->get()->mapWithKeys(function ($item , $key){
            return [$item->key => $item];
        });
        return [$scope , $keys];
    }

    function loadConfigFile($file="configSet.php"){
        $configs = require config_path($file);

        $scopes = ["" => ["title" => null , "description"=> null , "extras" => []]];

        $res = $this->formatConfigProperty("" , $configs);
        $scopes = array_merge_recursive($scopes, $res[0]);

        foreach ($scopes as $s=>&$v){
            if (is_array($v["title"])) $v["title"] = $v["title"][count($v["title"]) - 1];
            if (is_array($v["description"])) $v["description"] = $v["description"][count($v["description"]) - 1];
        }


        return [$scopes , $res[1]];
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
                continue;
            }else if ($config instanceof ESConfigDescription){
                $scopes[$scope]["description"] = $config->description;
            }else if ($config instanceof ESConfigExtras){
                $scopes[$scope]["extras"] = $config->extras;
            }else{
                if (! isset($scopes[$scope . "." .$key])) $scopes[$scope . "." .$key] = ["title" => null , "description"=> null , "extras" => []];

                $res = $this->formatConfigProperty($scope . "." .$key , $config);
                $scopes = array_merge_recursive($scopes, $res[0]);
                $keys = array_merge($keys, $res[1]);
            }
        }

        return [$scopes , $keys];
    }
}

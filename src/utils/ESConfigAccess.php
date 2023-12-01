<?php

namespace Gopex\EasySetConfig\utils;

use Gopex\EasySetConfig\database\models\Keys;
use Gopex\EasySetConfig\database\models\Scope;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use MongoDB\Driver\Query;
use \Illuminate\Database\Eloquent\Collection;

class ESConfigAccess implements IESAccess
{
    const CACHE_PREFIX = "easysetconfig";
    public function __construct(private readonly string $scope){}

    public function scope(string $scope): IESAccess{
        return new self($this->scope . "." . $scope);
    }

    public function get(string $key){
        $key =  $this->scope . "." . $key;
        $cache = Cache::store(config("easySetConfig.cache_driver") ?? config("cache.default"));

        if ($cache->has($key)){
            return $cache->get($key);
        }

        return $cache->rememberForever(self::CACHE_PREFIX . $key , function () use(&$key){
            return Keys::query()->where("key" , $key)->select("value" , "type")->firstOrNew()->value;
        });
    }

    public function set(string $key , $value): bool{
        $key =  $this->scope . "." . $key;
        $model = Keys::query()->where("key" , $key)->firstOrFail();
        $model->value = $value;

        if ($model->save()){
            Cache::store(config("easySetConfig.cache_driver") ?? config("cache.default"))->forget(self::CACHE_PREFIX . $key);
            return true;
        }

        return false;
    }

    public function forget(string $key): bool
    {
        return Cache::forget(self::CACHE_PREFIX . $this->scope . "." . $key);
    }

    public function forgetAll(): bool
    {
        $keys = Keys::query()->where("key" , "like" , $this->scope . "%")->pluck("key");
        $flag = true;
        foreach ($keys as $key){
            $flag = $flag && $this->forget($key);
        }

        return $flag;
    }

    public function getRaw(string $key): Model
    {
        return Keys::query()->where("key", $this->scope . "." . $key)->first();
    }

    public function getRawSubSet(): Collection
    {
        return Keys::query()->where("key", "like" , $this->scope . "%")->get();
    }

    public function getRawScopeSubSet(): Collection
    {
        return Scope::query()->where("scope", "like" , $this->scope . "%")->get();
    }
}

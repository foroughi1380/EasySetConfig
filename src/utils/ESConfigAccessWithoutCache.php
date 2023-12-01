<?php

namespace Gopex\EasySetConfig\utils;

use Gopex\EasySetConfig\database\models\Keys;
use Gopex\EasySetConfig\database\models\Scope;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ESConfigAccessWithoutCache implements IESAccess
{
    public function __construct(private readonly string $scope){}

    public function scope(string $scope): IESAccess{
        return new self($this->scope . "." . $scope);
    }

    public function get(string $key){
        $key =  $this->scope . "." . $key;
        return Keys::query()->where("key" , $key)->select("value" , "type")->firstOrNew()->value;
    }

    public function set(string $key , $value): bool{
        $key =  $this->scope . "." . $key;
        $model = Keys::query()->where("key" , $key)->firstOrFail();
        $model->value = $value;

        return $model->save();
    }

    public function forget(string $key): bool
    {
        return true;
    }

    public function forgetAll(): bool
    {
        return true;
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

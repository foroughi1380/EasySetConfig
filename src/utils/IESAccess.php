<?php

namespace Gopex\EasySetConfig\utils;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Collection;

interface IESAccess
{
    public function scope(string $scope): IESAccess;

    public function get(string $key);
    public function set(string $key , $value): bool;
    public function forget(string $key): bool;
    public function forgetAll(): bool;

    public function getRaw(string $key):Model;
    public function getRawSubSet():Collection;
    public function getRawScopeSubSet():Collection;

}

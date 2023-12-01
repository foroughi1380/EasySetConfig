<?php

namespace Gopex\EasySetConfig\utils;

interface IESAccess
{
    public function scope(string $scope): IESAccess;

    public function get(string $key);
    public function set(string $key , $value): bool;
    public function forget(string $key): bool;
    public function forgetAll(): bool;

}

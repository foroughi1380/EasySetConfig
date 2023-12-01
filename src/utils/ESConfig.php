<?php

namespace Gopex\EasySetConfig\utils;

class ESConfig
{
    public static function scope(string $scope): ESConfigAccess{
        return new ESConfigAccess($scope);
    }

    public static function get(string $key){
        return (new ESConfigAccess(""))->get($key);
    }
}

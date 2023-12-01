<?php

namespace Gopex\EasySetConfig\utils;

class ESConfigAccess
{
    public function __construct(private readonly string $scope){}

    public function scope(string $scope): ESConfigAccess{
        return new self($this->scope . "." . $scope);
    }

    public function get(string $key){
        return $this->scope;
    }
}

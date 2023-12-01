<?php

if (! function_exists(__NAMESPACE__ . '\esconfig_property')){
    function esconfig_property($type , $initValue, $title="" , $description="" , $extras = []): \Gopex\EasySetConfig\utils\config\ESConfigProperty
    {
        return new \Gopex\EasySetConfig\utils\config\ESConfigProperty($type , $initValue , $title , $description, $extras);
    }
}


if (! function_exists(__NAMESPACE__ . '\esconfig_title')){
    function esconfig_title($title): \Gopex\EasySetConfig\utils\config\ESConfigTitle
    {
        return new \Gopex\EasySetConfig\utils\config\ESConfigTitle($title);
    }
}

if (! function_exists(__NAMESPACE__ . '\esconfig_description')){
    function esconfig_description($description): \Gopex\EasySetConfig\utils\config\ESConfigDescription
    {
        return new \Gopex\EasySetConfig\utils\config\ESConfigDescription($description);
    }
}


if (! function_exists(__NAMESPACE__ . '\esconfig_extras')){
    function esconfig_extras($extras): \Gopex\EasySetConfig\utils\config\ESConfigExtras
    {
        return new \Gopex\EasySetConfig\utils\config\ESConfigExtras($extras);
    }
}

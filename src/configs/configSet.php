<?php

use \Gopex\EasySetConfig\utils\config\ESConfigProperty;

return [
    "maintenance" => [ // scope
        esconfig_title(""), // optional for set title to scope
        esconfig_description(""), // optional for set description to scope
        esconfig_extras([]), // optional , you can set any thins

        "enabled" => esconfig_property(ESConfigProperty::TYPE_BOOLEAN , false , "" , ""), // set properties like this

        "text" =>[ // scope
            //esconfig_title(""), // optional for set title to scope
            //esconfig_description(""), // optional for set description to scope
            //esconfig_extras([]), // optional , you can set any thins


            "message" => esconfig_property(ESConfigProperty::TYPE_STRING , "coming soon"), // title and description is optional,
            "time" => esconfig_property(ESConfigProperty::TYPE_DATE , "2024/12/11")
        ]
    ],
];

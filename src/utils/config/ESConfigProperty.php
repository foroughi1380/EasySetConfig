<?php

namespace Gopex\EasySetConfig\utils\config;

class ESConfigProperty
{
    const TYPE_FILE = "file",
        TYPE_PRIVATE_FILE = "private_file",
        TYPE_IMAGE = "image",
        TYPE_PRIVATE_IMAGE = "private_image",
        TYPE_MULTILINE = "multiline",
        TYPE_ARRAY = "array",
        TYPE_ANY = "any",
        TYPE_BOOLEAN = "boolean",
        TYPE_DATE = "date",
        TYPE_DATETIME = "datetime",
        TYPE_DOUBLE = "double",
        TYPE_FLOAT = "float",
        TYPE_INTEGER = "integer",
        TYPE_MONEY = "money",
        TYPE_STRING = "string";

    public function __construct(public string $type, public $initValue , public string $title , public string $description, public $extras){}
}

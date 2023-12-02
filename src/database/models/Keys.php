<?php

namespace Gopex\EasySetConfig\database\models;

use Gopex\EasySetConfig\database\casts\FileCast;
use Gopex\EasySetConfig\database\casts\PrivateFileCast;
use Gopex\EasySetConfig\database\casts\SerialCast;
use Gopex\EasySetConfig\utils\config\ESConfigProperty;
use Illuminate\Database\Eloquent\Model;

class Keys extends Model
{

    protected $table = "esconfig_keys";
    public $timestamps = false;
    protected $fillable = [
        "key",
        "value",
        "type",
        "title",
        "description",
        "extras"
    ];


    protected $casts = [
        "value" => "string",
        "extras" => "array"
    ];

    /**
     * @return string[]
     */
    public function getCasts(): array
    {
        $casts = parent::getCasts();

        if (empty($this->type)) return $casts;

        switch (strtolower($this->type)) {
            case ESConfigProperty::TYPE_ARRAY:
            case ESConfigProperty::TYPE_ANY:
                $valueCast = SerialCast::class;
                break;
            case ESConfigProperty::TYPE_MULTILINE:
            case ESConfigProperty::TYPE_COMBOBOX:
            case ESConfigProperty::TYPE_STRING_WITH_SUGGEST:
                $valueCast = "string";
                break;
            case ESConfigProperty::TYPE_MONEY:
                $valueCast = "double";
                break;
            case ESConfigProperty::TYPE_IMAGE:
            case ESConfigProperty::TYPE_FILE:
                $valueCast = FileCast::class;
                break;
            case ESConfigProperty::TYPE_PRIVATE_IMAGE:
            case ESConfigProperty::TYPE_PRIVATE_FILE:
                $valueCast = PrivateFileCast::class;
                break;
            default:
                $valueCast = $this->type;
        }

        $casts['value'] = $valueCast ?? $casts['value'];
        return $casts;
    }

}

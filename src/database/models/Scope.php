<?php

namespace Gopex\EasySetConfig\database\models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Gopex\EasySetConfig\database\casts\SerialCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Scope extends Model
{
    protected $fillable = [
        "scope",
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
            case "array":
            case "any":
                $valueCast = SerialCast::class;
                break;
            case "multiline":
                $valueCast = "string";
                break;
            default:
                $valueCast = $this->type;
        }

        $casts['value'] = $valueCast ?? $casts['value'];
        return $casts;
    }

}

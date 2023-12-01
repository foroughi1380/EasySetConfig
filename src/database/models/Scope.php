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

    protected $table = "esconfig_scopes";
    public $timestamps = false;
    protected $fillable = [
        "scope",
        "title",
        "description",
        "extras"
    ];


    protected $casts = [
        "extras" => "array"
    ];

}

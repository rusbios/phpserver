<?php

namespace RusBios\Modules;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static ConfigModule find(string $string)
 * @property array param
 * @property string name
 * @property DateTime updated_at
 * @property DateTime created_at
 */
class ConfigModule extends Model
{
    protected $table = 'config';
    protected $keyType = 'string';
    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];
}

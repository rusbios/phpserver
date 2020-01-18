<?php

namespace RusBios\Modules;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static ConfigModule find(int $id)
 * @property int id
 * @property string command
 * @property array arguments
 * @property int|null pid
 * @property DateTime updated_at
 * @property DateTime created_at
 */
class RunCommand extends Model
{
    protected $table = 'run_command';
    protected $dates = [
        self::CREATED_AT,
        self::UPDATED_AT,
    ];
    public function toArray()
    {
        return [
            'id' => $this->id,
            'command' => $this->command,
            'arguments' => $this->attributes['arguments'],
            'pid' => (string) $this->pid,
            'created_at' => $this->created_at->format('H:i:s d-m-Y'),
            'updated_at' => $this->updated_at->format('H:i:s d-m-Y'),
        ];
    }

    /**
     * @param string $command
     * @param array $arguments
     * @return bool
     */
    public static function add($command, array $arguments = null)
    {
        if(RunCommand::query()
            ->where('command', $command)
            ->where('arguments', json_encode($arguments))
            ->first()) return true;

        $runCommand = new RunCommand();
        $runCommand->command = $command;
        $runCommand->arguments = $arguments;
        return $runCommand->save();
    }

    public function setArgumentsAttribute($arguments = null)
    {
        $this->attributes['arguments'] = json_encode(($arguments ?: []));
        return $this;
    }

    public function getArgumentsAttribute()
    {
        return json_decode($this->attributes['arguments'], true);
    }
}

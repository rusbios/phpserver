<?php

namespace RusBios\Modules\Commands;

use Illuminate\Console\Command;
use RusBios\Modules\RunCommand;

class ModulesRunCommand extends Command
{
    protected $signature = 'rusbios:run-commands {--run=false} {--kill_all=false}';

    protected $description = 'manager child module commands';

    public function handle()
    {
        if ($this->option('kill_all')) {
            foreach (RunCommand::all() as $kill) {
                if ($kill->pid && posix_getpgid($kill->pid)) {
                    posix_kill($kill->pid, 9);
                    $kill->pid = null;
                    $kill->save();
                }
            }
        }

        if ($this->option('run')) {
            foreach (RunCommand::all() as $run) {
                if ($run->pid && !posix_getpgid($run->pid)) continue;
                $run->pid = $this->call($run->command, $run->arguments) ?: null;
                if ($run->pid) {
                    $this->info(sprintf(
                        '%s arguments %s pid:%s',
                        $run->command,
                        $run->getAttribute('arguments'),
                        (string)$run->pid
                    ));
                }
            }
        }

        $this->table(['id', 'command', 'arguments', 'pid', 'created_at', 'updated_at'], RunCommand::all()->toArray());
    }
}

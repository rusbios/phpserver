<?php

namespace RusBios\Modules;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use RusBios\Modules\Commands\ModulesRunCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Config::set('database.connections.laravel_module', [
            'driver' => 'sqlite',
            'database' => database_path(env('RUSBIOS_DATABASE', 'laravelModule.sqlite')),
            'foreign_key_constraints' => true,
        ]);
        if (!file_exists(config('database.connections.laravel_module.database'))) {
            file_put_contents(config('database.connections.laravel_module.database'), '');
        }

        $this->loadMigrationsFrom([
            __DIR__ . '/migrations/2019_12_03_000000_create_config_table.php',
            __DIR__ . '/migrations/2020_05_01_000000_create_run_command_table.php',
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ModulesRunCommand::class,
            ]);
        }
    }
}

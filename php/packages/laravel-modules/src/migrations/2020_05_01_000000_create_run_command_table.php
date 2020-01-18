<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRunCommandTable extends Migration
{
    public function up()
    {
        Schema::connection('laravel_module')->create('run_command', function (Blueprint $table) {
            $table->increments('id');
            $table->string('command');
            $table->json('arguments');
            $table->integer('pid')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('laravel_module')->dropIfExists('run_command');
    }
}

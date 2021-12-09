<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePladsTable extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_plads', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vork_kurser_plads');
    }
}

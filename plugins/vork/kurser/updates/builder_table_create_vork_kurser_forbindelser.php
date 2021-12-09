<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserForbindelser extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_forbindelser', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('materiale_id');
            $table->integer('kasse_id');
            $table->integer('antal');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_forbindelser');
    }
}

<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserBestillinger extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_bestillinger', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('gruppe_id');
            $table->integer('user_id');
            $table->integer('materiale_id');
            $table->integer('antal');
            $table->dateTime('start');
            $table->dateTime('slut');
            $table->text('kommentar_user')->nullable();
            $table->boolean('medbringer');
            $table->text('kommentar_smedene')->nullable();
            $table->boolean('godkendt')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_bestillinger');
    }
}

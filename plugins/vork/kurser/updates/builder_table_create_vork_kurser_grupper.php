<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserGrupper extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_grupper', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('kursus_id');
            $table->string('navn');
            $table->smallInteger('nummer');
            $table->string('image')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_grupper');
    }
}

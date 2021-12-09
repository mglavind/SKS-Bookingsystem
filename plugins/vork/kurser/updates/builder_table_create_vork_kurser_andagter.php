<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserMaaltider extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_andagter', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('kursus_id');
            $table->string('navn');
            $table->integer('sortering');
            $table->string('tovholder');
            $table->text('medlemmer');
            $table->text('sted');
            $table->text('musik');
            $table->text('koekken');
            $table->text('teknik');
            $table->text('materialer');
            $table->text('beskrivelse');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_andagter');
    }
}

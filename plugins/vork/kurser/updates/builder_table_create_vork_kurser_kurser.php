<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserKurser extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_kurser', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('aar');
            $table->enum('slags', ['Påske', 'Efterår'])->default('Påske');
            $table->date('start');
            $table->date('slut');
            $table->string('image')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_kurser');
    }
}

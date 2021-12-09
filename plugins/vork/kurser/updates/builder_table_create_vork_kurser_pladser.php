<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserPladser extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_pladser', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('omraade_id');
            $table->string('navn');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_pladser');
    }
}

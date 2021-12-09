<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserMaterialer extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_materialer', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('navn');
            $table->string('enhed');
            $table->boolean('skaffe')->default(false);
            $table->boolean('forbrug')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_materialer');
    }
}

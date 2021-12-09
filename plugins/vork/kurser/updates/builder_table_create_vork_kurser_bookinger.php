<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserBookinger extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_bookinger', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('bookable_id');
            $table->integer('gruppe_id');
            $table->datetime('start');
            $table->datetime('slut');
            $table->string('kommentar');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_bookinger');
    }
}

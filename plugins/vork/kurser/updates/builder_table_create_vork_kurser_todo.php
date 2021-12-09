<?php namespace Vork\Kurser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateVorkKurserTodo extends Migration
{
    public function up()
    {
        Schema::create('vork_kurser_todo', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('gruppe_id');
            $table->string('tekst');
            $table->string('ansvarlig');
            $table->text('kommentar');
            $table->string('color')->default("#1abc9c");
            $table->boolean('done')->default(false);
            $table->integer('created_by');
            $table->integer('done_by')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->integer('sortering')->default(99);
            $table->timestamp('deadline')->nullable();
            $table->integer('faelles')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('vork_kurser_todo');
    }
}

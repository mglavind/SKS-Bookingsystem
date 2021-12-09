<?php namespace RainLab\UserPlus\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UserAddProfileFields extends Migration
{

    public function up()
    {
        if (Schema::hasColumns('users', ['kredsnavn', 'medlemsnummer'])) {
            return;
        }

        Schema::table('users', function($table)
        {
            $table->string('kredsnavn', 100)->nullable();
            $table->string('medlemsnummer', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn(['kredsnavn', 'medlemsnummer']);
        });
    }

}

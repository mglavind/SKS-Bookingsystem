<?php namespace Vdomah\RolesRainLabUser\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UsersAddRoleId extends Migration
{
    public $table = 'users';

    public $column = 'vdomah_role_id';

    public function up()
    {
        if (!Schema::hasTable($this->table) ||
            Schema::hasColumn($this->table, $this->column)
        )
            return;

        Schema::table($this->table, function($table)
        {
            $table->integer($this->column)->nullable();
        });
    }

    public function down()
    {
        if (!Schema::hasTable($this->table) ||
            !Schema::hasColumn($this->table, $this->column)
        )
            return;

        Schema::table($this->table, function($table)
        {
            $table->dropColumn($this->column);
        });
    }
}
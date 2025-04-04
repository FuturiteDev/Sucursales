<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnColorToSucursalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasTable('sucursales')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->text('color')->default('0xFF000000')->after('matriz');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('sucursales')) {
            Schema::table('sucursales', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
    }
}

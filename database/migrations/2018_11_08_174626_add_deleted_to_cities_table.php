<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedToCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('provinces', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('villages', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('districts', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
        Schema::table('villages', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}

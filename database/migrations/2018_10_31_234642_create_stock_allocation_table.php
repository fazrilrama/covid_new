<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_allocations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('storage_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('type');
            $table->float('allocated');
            $table->integer('project_id')->unsigned();
            $table->float('volume');
            $table->float('weight');
            $table->date('control_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_allocations');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDeliveryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_delivery_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_delivery_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->float('qty')->nullable();
            $table->float('weight')->nullable();
            $table->float('volume')->nullable();
            $table->date('control_date')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('stock_delivery_details');
    }
}

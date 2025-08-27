<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdvanceNoticeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_advance_notice_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_advance_notice_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('uom_id')->unsigned();
            $table->float('qty')->nullable();
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
        Schema::dropIfExists('stock_advance_notice_details');
    }
}

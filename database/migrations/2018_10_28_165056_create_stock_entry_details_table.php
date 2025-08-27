<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockEntryDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_entry_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_entry_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('storage_id')->unsigned()->nullable();
            $table->integer('uom_id')->unsigned();
            $table->date('control_date')->nullable();
            $table->float('volume')->nullable();
            $table->float('weight')->nullable();
            $table->float('begining_qty')->nullable();
            $table->float('qty')->nullable();
            $table->float('ending_qty')->nullable();
            $table->string('status')->default('draft');
            $table->string('ref_code')->nullable();
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
        Schema::dropIfExists('stock_entry_details');
    }
}

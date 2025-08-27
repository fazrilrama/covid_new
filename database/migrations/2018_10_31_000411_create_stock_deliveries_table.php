<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('code')->nullable();
            $table->integer('stock_entry_id')->unsigned();
            $table->integer('origin_id')->nullable();
            $table->integer('destination_id')->nullable();
            $table->string('ref_code')->nullable();
            $table->integer('transport_type_id')->unsigned();
            $table->string('vehicle_code_num');
            $table->string('vehicle_plate_num');
            $table->date('etd');
            $table->date('eta');
            $table->integer('shipper_id')->unsigned();
            $table->string('shipper_address')->nullable();
            $table->string('shipper_postal_code')->nullable();
            $table->integer('consignee_id')->unsigned();
            $table->string('consignee_address')->nullable();
            $table->string('consignee_postal_code')->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('total_collie')->nullable();
            $table->float('total_weight')->nullable();
            $table->float('total_volume')->nullable();
            $table->integer('project_id')->unsigned();
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
        Schema::dropIfExists('stock_deliveries');
    }
}

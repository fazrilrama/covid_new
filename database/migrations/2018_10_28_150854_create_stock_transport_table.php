<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('code')->nullable();
            $table->integer('advance_notice_id')->unsigned();
            $table->date('etd');
            $table->date('eta');
            $table->integer('origin_id')->nullable();
            $table->integer('destination_id')->nullable();
            $table->string('ref_code')->nullable();
            $table->integer('transport_type_id')->unsigned();
            $table->string('vehicle_code_num')->nullable();
            $table->string('vehicle_plate_num')->nullable();
            $table->integer('shipper_id')->unsigned();
            $table->string('shipper_address')->nullable();
            $table->integer('consignee_id')->unsigned();
            $table->string('consignee_address')->nullable();
            $table->string('employee_name')->nullable();
            $table->integer('user_id')->unsigned();
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
         Schema::dropIfExists('stock_transports');
    }
}

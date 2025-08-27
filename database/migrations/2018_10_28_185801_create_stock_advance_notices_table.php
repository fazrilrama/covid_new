<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAdvanceNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_advance_notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('code')->nullable();
            $table->integer('advance_notice_activity_id')->unsigned();
            $table->date('etd');
            $table->date('eta');
            $table->integer('origin_id')->unsigned();
            $table->integer('destination_id')->unsigned();
            $table->string('ref_code')->nullable();
            $table->integer('transport_type_id')->unsigned();
            $table->integer('shipper_id')->unsigned();
            $table->string('shipper_address')->nullable();
            $table->integer('consignee_id')->unsigned();
            $table->string('consignee_address')->nullable();
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
        Schema::dropIfExists('stock_advance_notices');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
           Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('postal_code')->nullable();
            $table->integer('city_id')->unassigned();
            $table->integer('region_id')->unassigned();
            $table->integer('company_id')->unassigned();
            $table->double('total_volume')->nullable();
            $table->double('total_weight')->nullable();
            $table->double('length')->nullable();
            $table->double('width')->nullable();
            $table->double('tall')->nullable();
            $table->boolean('is_active');
            $table->double('used_volume')->nullable();
            $table->double('used_weight')->nullable();
            $table->string('ownership')->nullable();
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
        Schema::dropIfExists('warehouses');
    }
}

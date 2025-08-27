<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('row')->nullable();
            $table->integer('column')->nullable();
            $table->integer('level')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable(); 
            $table->float('volume')->nullable();
            $table->float('weight')->nullable();
            $table->float('used_volume')->nullable();
            $table->float('used_weight')->nullable();
            $table->integer('warehouse_id')->unsigned();            
            $table->string('notes')->nullable();
            $table->boolean('is_active');            
            $table->integer('commodity_id')->unsigned();
            $table->boolean('is_available')->default(true);      
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
        Schema::dropIfExists('storages');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_transport_id')->unsigned();
            $table->string('type');
            $table->string('code')->nullable();        
            $table->double('total_pallet')->nullable();   
            $table->double('total_labor')->nullable();
            $table->double('total_forklift')->nullable();  
            $table->datetime('forklift_start_time')->nullable(); 
            $table->datetime('forklift_end_time')->nullable();
            $table->string('ref_code')->nullable();
            $table->string('employee_name')->nullable();
            $table->integer('warehouse_id')->unsigned();  
            $table->integer('user_id')->unsigned();
            $table->string('status')->default('draft');
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
        Schema::dropIfExists('stock_entries');
    }
}

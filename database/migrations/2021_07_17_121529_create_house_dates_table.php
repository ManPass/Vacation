<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_dates', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer("date_id", unsigned:true);
            $table->integer("house_id", unsigned:true);
            $table->integer("weekday_price", unsigned:true);
            $table->integer("weekend_price", unsigned:true);
       
            $table->foreign("house_id")->references("id")->on("houses")->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('date_id')->references('id')->on('dates')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('house_dates');
    }
}

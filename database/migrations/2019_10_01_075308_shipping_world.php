<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShippingWorld extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_world', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('shipping_id');
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('cascade')->onUpdate('cascade');
            $table->string('relation', 10)->comment('country, province, city, town, region');
            $table->unsignedBigInteger('relation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddressComplete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('town_id')->after('city_id')->nullable();
            $table->foreign('town_id')->references('id')->on('towns')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('region_id')->after('town_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('success_orders')->default(0)->after('postal_code');
            $table->string('status', 1)->default(1)->after('postal_code');
            $table->dropForeign(['shingle_id']);
            $table->dropColumn(['shingle_id']);
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

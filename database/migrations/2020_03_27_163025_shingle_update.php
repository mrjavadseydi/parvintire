<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShingleUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shingles', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn(['city_id', 'name', 'active_postage', 'latitude', 'longitude']);
            $table->string('relation', 25)->after('id')->comment('country|province|city|town|region');
            $table->unsignedBigInteger('relation_id')->after('relation');
            $table->unsignedTinyInteger('shipping_id')->nullable()->after('relation_id');
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('set null')->onUpdate('cascade');
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

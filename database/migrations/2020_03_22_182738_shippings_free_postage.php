<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShippingsFreePostage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->renameColumn('free_price', 'free_postage');
        });
        Schema::table('order_shippings', function (Blueprint $table) {
            $table->double('free_postage')->unsigned()->default(0)->after('shipping_id');
            $table->string('status', 1)->default(1)->after('postage');
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

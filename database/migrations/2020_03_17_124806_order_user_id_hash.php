<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderUserIdHash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('hash', 32)->nullable()->after('user_id');
            $table->double('price')->default(0)->after('hash');
            $table->double('discount')->default(0)->after('price');
            $table->double('payed_price')->default(0)->after('discount');
            $table->string('gift', 150)->nullable()->after('payed_price');
            $table->string('status', 1)->default('0')->after('address_id');
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

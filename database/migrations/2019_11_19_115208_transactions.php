<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('relation', 25);
            $table->string('relation_id', 20);
            $table->string('gateway', 25);
            $table->string('currency', 50)->nullable();
            $table->foreign('currency')->references('code')->on('currency')->onDelete('set null')->onUpdate('cascade');
            $table->double('price');
            $table->string('status', 1)->default('0');
            $table->string('payed', 1)->default('0');
            $table->text('reference_id')->nullable();
            $table->text('token', 32)->nullable();
            $table->text('authority')->nullable();
            $table->longText('information')->nullable();
            $table->unsignedInteger('code')->nullable();
            $table->ipAddress('ip');
            $table->timestamps();
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

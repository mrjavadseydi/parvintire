<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shippings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shippings', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->tinyIncrements('id');
            $table->text('title');
            $table->text('description');
            $table->string('currency', 50)->nullable();
            $table->foreign('currency')->references('code')->on('currency')->onDelete('set null')->onUpdate('cascade');
            $table->double('free_price')->unsigned()->default(0);
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

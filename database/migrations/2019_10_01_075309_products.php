<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('product_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->text('title')->nullable();
            $table->string('type', 10)->default('postal');
            $table->unsignedTinyInteger('shipping_id')->nullable();
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('set null')->onUpdate('cascade');
            $table->unsignedTinyInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('set null')->onUpdate('cascade');
            $table->double('price')->default(0);
            $table->double('special_price')->default(0)->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->smallInteger('stock')->default(0)->nullable();
            $table->unsignedTinyInteger('sort')->default(0)->nullable();
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

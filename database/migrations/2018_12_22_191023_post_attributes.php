<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_attributes', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->string('type', 25)->comment('post|comment');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('key_id')->nullable();
            $table->foreign('key_id')->references('id')->on('attribute_keys')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('value_id')->nullable();
            $table->foreign('value_id')->references('id')->on('attribute_values')->onDelete('cascade')->onUpdate('cascade');
            $table->string('active', 1)->default('1');
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

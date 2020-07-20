<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_types', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->increments('id');
            $table->string('label', 100)->nullable();
            $table->string('total_label', 100)->nullable();
            $table->string('type', 100)->unique();
            $table->text('icon', 50)->nullable();
            $table->string('color', 10)->nullable();
            $table->longText('boxes')->nullable();
            $table->longText('validations')->nullable();
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

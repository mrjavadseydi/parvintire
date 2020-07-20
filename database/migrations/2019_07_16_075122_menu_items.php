<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menu_items', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade')->onUpdate('cascade');
            $table->text('title');
            $table->unsignedBigInteger('parent')->nullable();
            $table->float('sort')->default(0);
            $table->text('link')->nullable();
            $table->string('type', 30)->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            # json value : example => ["href", "target", "rel", ...]
            $table->longText('attributes')->comment('json:{href,target,rel,...}')->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('class', 50)->nullable();
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

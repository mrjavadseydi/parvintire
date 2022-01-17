<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('comments', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('set null');
            $table->unsignedBigInteger('post_id')->nullable();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('set null');
            $table->unsignedBigInteger('parent')->nullable();
            $table->foreign('parent')->references('id')->on('comments')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type', 1)->default('1')->comment('1:comment|2:ticket');
            $table->text('subject')->nullable();
            $table->text('comment');
            $table->unsignedTinyInteger('department')->nullable();
            $table->text('priority', 1)->nullable()->comment('1:much|2:medium|3:low');
            $table->ipAddress('ip');
            $table->string('status', 1)->default('1');
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

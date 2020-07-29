<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Files extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('product_files');
        Schema::create('files', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('attachment_id');
            $table->foreign('attachment_id')->references('id')->on('attachments')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('group_id');
            $table->foreign('group_id')->references('id')->on('files_groups')->onDelete('cascade')->onUpdate('cascade');
            $table->string('title');
            $table->unsignedSmallInteger('episode')->default(1);
            $table->string('server', 50)->comment('serverKey');
            $table->string('type', 1)->comment('0:free|1:auth|2:vip|3:cash')->nullable();
            $table->string('status', 1)->comment('0:deActive|1:active')->nullable();
            $table->string('note', 50)->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
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

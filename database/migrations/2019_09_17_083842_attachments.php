<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Attachments extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('poster')->nullable();
            $table->string('mime', 150);
            $table->string('type', 50);
            $table->string('extension', 50);
            $table->unsignedInteger('size')->default(0);
            $table->unsignedInteger('duration')->default(0);
            $table->string('path', 150);
            $table->unsignedBigInteger('parent')->nullable();
            $table->foreign('parent')->references('id')->on('attachments')->onDelete('cascade')->onUpdate('cascade');
            $table->string('in_public', 1)->default(1);
            $table->timestamp('deleted_at')->nullable();
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

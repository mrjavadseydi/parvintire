<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('posts', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('slug', 100)->unique();
            $table->text('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail', 150)->nullable();
            $table->string('lang', 5)->default('fa');
            $table->unsignedBigInteger('parent')->nullable();
            $table->string('post_type', 100)->nullable();
            $table->foreign('post_type')->references('type')->on('post_types')->onDelete('set null')->onUpdate('cascade');
            $table->string('status', 50)->default('draft');
            $table->string('final_status', 50)->default('draft');
            $table->timestamp('published_at')->nullable();
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

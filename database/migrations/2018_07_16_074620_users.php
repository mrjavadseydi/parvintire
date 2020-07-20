<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->bigIncrements('id');
            $table->string('username', 100)->unique()->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('mobile', 11)->unique()->nullable();
            $table->char('password', 100);
            $table->string('name')->nullable();
            $table->string('family')->nullable();
            //            $table->char('level', 50)->default('member');
            $table->text('avatar')->nullable();
            $table->char('gender', 1)->comment('1=>male|2=>female')->nullable();
            $table->date('birthday')->nullable();
            $table->rememberToken()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('logined_at')->comment('this means last_login')->nullable();
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
        Schema::dropIfExists('users');
    }

}

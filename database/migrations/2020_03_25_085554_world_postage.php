<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WorldPostage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->double('postage')->unsigned()->default(0)->after('keywords');
            $table->string('active_postage', 1)->default('0')->after('postage');
        });
        Schema::table('towns', function (Blueprint $table) {
            $table->double('postage')->unsigned()->default(0)->after('keywords');
            $table->string('active_postage', 1)->default('0')->after('postage');
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->double('postage')->unsigned()->default(0)->after('keywords');
            $table->string('active_postage', 1)->default('0')->after('postage');
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

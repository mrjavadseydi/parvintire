<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('categories', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('image');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('type');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('attributes')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('attribute_keys', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('icon');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('attribute_keys')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('attribute_values', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('icon');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('attribute_values')->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::table('cities', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('longitude');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('longitude');
            $table->unsignedInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('longitude');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('provinces')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('towns', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('longitude');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('id')->on('towns')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('sort');
            $table->unsignedBigInteger('parent')->nullable()->after('lang');
            $table->foreign('parent')->references('product_id')->on('products')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('menu_metas', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('more');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->string('lang', 5)->default('fa')->after('more');
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

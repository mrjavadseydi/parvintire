<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductSalesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
          CREATE VIEW product_sales AS
          (
            SELECT carts.product_id, SUM(carts.count) as count FROM `carts`
            LEFT JOIN orders ON carts.order_id = orders.id
            WHERE orders.status = '1'
            GROUP BY carts.product_id
          )
        ");
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

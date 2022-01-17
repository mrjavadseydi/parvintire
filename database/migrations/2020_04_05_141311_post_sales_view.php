<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostSalesView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
          CREATE VIEW post_sales AS
          (
            SELECT products.post_id, SUM(carts.count) as count FROM `carts`
            LEFT JOIN orders ON carts.order_id = orders.id
            LEFT JOIN products ON carts.product_id = products.product_id
            WHERE orders.status = '1'
            GROUP BY products.post_id
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 15);
            $table->dateTime('tgl');
            $table->unsignedInteger('cust_id');
            $table->foreign('cust_id')->references('id')->on('m_customers');
            $table->decimal('subtotal', 9, 2);
            $table->decimal('diskon', 9, 2);
            $table->decimal('ongkir', 9, 2);
            $table->decimal('total_bayar', 9, 2);
            $table->string('status', 2)->default('1');
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
        // Schema::dropForeign(['cust_id']);

        Schema::dropIfExists('t_sales');


    }
}

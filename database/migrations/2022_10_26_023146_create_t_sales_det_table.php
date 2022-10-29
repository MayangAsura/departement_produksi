<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSalesDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sales_det', function (Blueprint $table) {
            $table->unsignedInteger('sales_id');
            $table->unsignedInteger('barang_id');
            $table->foreign('sales_id')->references('id')->on('t_sales');
            $table->foreign('barang_id')->references('id')->on('m_barangs');
            $table->decimal('harga_bandrol', 9, 2);
            $table->integer('qty');
            $table->decimal('diskon_pct', 9, 2)->nullable();
            $table->decimal('diskon_nilai', 9, 2)->nullable();
            $table->decimal('harga_diskon', 9, 2);
            $table->decimal('total', 9, 2);
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
        Schema::dropIfExists('t_sales_det');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_barangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode', 10);
            $table->string('nama', 100);
            $table->decimal('harga', 9, 2);
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
        Schema::dropIfExists('m_barangs');
    }
}

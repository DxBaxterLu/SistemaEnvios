<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcategoria')->unsigned();
            $table->integer('idtransporte')->unsigned();
            $table->integer('idtienda')->unsigned();
            $table->string('descripcion',256);
            $table->integer('stock');
            $table->boolean('condicion')->default(1);
            $table->timestamps();

            $table->foreign('idcategoria')->references('id')->on('categorias');
            $table->foreign('idtransporte')->references('id')->on('transportes');
            $table->foreign('idtienda')->references('id')->on('tiendas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alertas');
    }
}

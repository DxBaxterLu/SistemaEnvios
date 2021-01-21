<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleEnviosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_envios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idenvio')->unsigned();
            $table->integer('idalerta')->unsigned();
            $table->decimal('peso',11,2);
            $table->integer('cantidad');
            $table->decimal('descuento',11,2);

            $table->foreign('idenvio')->references('id')->on('envios')->onDelete('cascade');
            $table->foreign('idalerta')->references('id')->on('alertas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_envios');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleEnvio extends Model
{
    //
    protected $table = 'detalle_envios';

    protected $fillable = [
        'idenvio',
        'idalerta',
        'peso',
        'cantidad',
        'descuento'
    ];
    public $timestamps = false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    //
    protected $table = 'envios';

    protected $fillable = [
        'idusuario',
        'tipo_identificacion',
        'num_envio',
        'fecha_envio',
        'impuesto',
        'total',
        'estado'
    ];

}

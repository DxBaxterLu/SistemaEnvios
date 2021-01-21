<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    //
    protected $table = 'tiendas';

    protected $fillable = ['nombre','sitio_web','telefono','pais','descripcion','condicion'];

    public function alertas(){
        return $this->hasMany('App\Alerta');
    }
}

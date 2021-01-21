<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transporte extends Model
{
    //
    protected $table = 'transportes';

    protected $fillable = ['nombre','telefono','origen','email','descripcion','condicion'];

    public function alertas(){
        return $this->hasMany('App\Alerta');
    }
}

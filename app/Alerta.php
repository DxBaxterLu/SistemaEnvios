<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    //
    protected $table = 'alertas';

    protected $fillable = ['idcategoria','idtransporte','idtienda','descripcion','stock','condicion'];

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }

    public function transporte(){
        return $this->belongsTo('App\Transporte');
    }

    public function tienda(){
        return $this->belongsTo('App\Tienda');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tienda;

class TiendaController extends Controller
{
    //
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $tiendas= Tienda::orderBy('id','desc')->paginate(10);
        } else {
            $tiendas = Tienda::where($criterio,'like','%'.$buscar.'%')->orderBy('id','desc')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $tiendas->total(),
                'current_page' => $tiendas->currentPage(),
                'per_page' => $tiendas->perPage(),
                'last_page' => $tiendas->lastPage(),
                'from' => $tiendas->firstItem(),
                'to' => $tiendas->lastItem()
            ], 'tiendas' => $tiendas
        ];
    }

    public function selectTienda(Request $request){
        if (!$request->ajax()) return redirect('/');
        $tiendas = Tienda::where('condicion','=','1')->select('id','nombre')->orderBy('nombre','asc')->get();

        return ['tiendas' => $tiendas];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tienda = new Tienda();
        $tienda->nombre = $request->nombre;
        $tienda->sitio_web = $request->sitio_web;
        $tienda->telefono = $request->telefono;
        $tienda->pais = $request->pais;
        $tienda->descripcion = $request->descripcion;
        $tienda->condicion = '1';
        $tienda->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tienda = Tienda::findOrFail($request->id);
        $tienda->nombre = $request->nombre;
        $tienda->sitio_web = $request->sitio_web;
        $tienda->telefono = $request->telefono;
        $tienda->pais = $request->pais;
        $tienda->descripcion = $request->descripcion;
        $tienda->condicion = '1';
        $tienda->save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tienda = Tienda::findOrFail($request->id);
        $tienda->condicion = '0';
        $tienda->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $tienda = Tienda::findOrFail($request->id);
        $tienda->condicion = '1';
        $tienda->save();
    }
}
